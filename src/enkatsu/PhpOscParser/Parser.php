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
    private $messages;

    function __construct()
    {
        $this->messages = new Collection();
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function parse(Collection $buf, int &$pos, int $endPos, int $timestamp=1)
    {
        $first = Reader::parseString($buf, $pos);
        if ($first == Identifier::$BUNDLE)
            $this->parseBundle($buf, $pos, $endPos);
        else
            $this->messages->push(new Message($first, $timestamp, $this->parseData($buf, $pos)));
        if ($pos != $endPos)
            error_log("The parsed data size is inconsitent with the given size: ${pos} / ${endPos}".PHP_EOL);
    }

    private function parseBundle(Collection $buf, int &$pos, int $endPos)
    {
        $time = Reader::parseTimetag($buf, $pos);
        while ($pos < $endPos)
        {
            $contentSize = Reader::parseInt($buf, $pos);
            if (Util::isMultipleOfFour($contentSize))
            {
                $this->parse($buf, $pos, $pos + $contentSize, $time);
            }
            else
            {
                error_log("Given data is invalid (bundle size (${contentSize}) is not a multiple of 4).".PHP_EOL);
                $pos += $contentSize;
            }
        }
    }

    public function parseData(Collection $buf, int &$pos): Collection
    {
        // remove ','
        $types = \substr(Reader::parseString($buf, $pos), 1);
        $n = strlen($types);
        if ($n == 0) return new Collection();
        $data = new Collection();
        for ($i = 0; $i < $n; ++$i)
        {
            switch (types[i])
            {
                case Identifier::$INT    : $data->push(Reader::parseInt($buf, $pos));    break;
                case Identifier::$FLOAT  : $data->push(Reader::parseFloat($buf, $pos));  break;
                case Identifier::$STRING : $data->push(Reader::parseString($buf, $pos)); break;
                case Identifier::$BLOB   : $data->push(Reader::parseBlob($buf, $pos));   break;
                default:
                    // Add more types here if you want to handle them.
                    break;
            }
        }
        return $data;
    }
}
