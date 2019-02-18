<?php
namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Identifier
{
    public static $BUNDLE = '#bundle';
    public static $INT    = 'i';
    public static $FLOAT  = 'f';
    public static $STRING = 's';
    public static $BLOB   = 'b';
}

class Parser
{
    function __construct()
    {
    }

    public function parse(Collection $buf, int &$pos, int $endPos, int $timestamp=1): ElementInterface
    {
        if ($pos > $endPos)
            throw new ParseException("The parsed data size is inconsitent with the given size: ${pos} / ${endPos}".PHP_EOL);

        $first = Reader::parseString($buf, $pos);
        if ($first == Identifier::$BUNDLE)
            return $this->parseBundle($buf, $pos, $endPos);
        else
            return new Message($first, $timestamp, $this->parseData($buf, $pos));
    }

    private function parseBundle(Collection $buf, int &$pos, int $endPos): Bundle
    {
        $time = Reader::parseTimetag($buf, $pos);
        $bundle = new Bundle($time);
        while ($pos < $endPos)
        {
            $contentSize = Reader::parseInt($buf, $pos) / 2;
            $element = $this->parse($buf, $pos, $pos + $contentSize, $time);
            $bundle->push($element);
            // if (Util::isMultipleOfFour($contentSize))
            // {
            //     $this->parse($buf, $pos, $pos + $contentSize, $time);
            // }
            // else
            // {
            //     error_log("Given data is invalid (bundle size (${contentSize}) is not a multiple of 4).".PHP_EOL);
            //     $pos += $contentSize;
            // }
        }
        return $bundle;
    }

    public function parseData(Collection $buf, int &$pos): Collection
    {
        $types = trim(Reader::parseString($buf, $pos), ',');
        if (strlen($types) == 0) return new Collection();
        $types = collect(str_split($types))->filter(function($type)
        {
            return in_array($type, [Identifier::$INT, Identifier::$FLOAT, Identifier::$STRING, Identifier::$BLOB]);
        });
        return $types->map(function($type) use ($buf, &$pos)
        {
            switch ($type)
            {
                case Identifier::$INT:
                    return Reader::parseInt($buf, $pos);
                    break;
                case Identifier::$FLOAT:
                    return Reader::parseFloat($buf, $pos);
                    break;
                case Identifier::$STRING:
                    return Reader::parseString($buf, $pos);
                    break;
                case Identifier::$BLOB:
                    return Reader::parseBlob($buf, $pos);
                    break;
                default:
                    throw new ParseException("The parsed data type is undefined: ${type}".PHP_EOL);
                    break;
            }
        });
    }
}
