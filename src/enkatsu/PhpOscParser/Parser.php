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
        if ($first == Identifier::$BUNDLE) {
            $this->parseBundle($buf, $pos, $endPos);
        } else {
            $message = new Message($first, $timestamp, $this->parseData($buf, $pos));
            $this->messages->push($message);
        }
        if ($pos != $endPos) {
            error_log("The parsed data size is inconsitent with the given size: ${pos} / ${endPos}".PHP_EOL);
        }
    }

    private function parseBundle(Collection $buf, int &$pos, int $endPos)
    {
        $time = Reader::parseTimetag($buf, $pos);
        var_dump('$time');
        var_dump($time);
        while ($pos < $endPos)
        {
            $contentSize = Reader::parseInt($buf, $pos) / 2;
            $this->parse($buf, $pos, $pos + $contentSize, $time);
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
    }

    public function parseData(Collection $buf, int &$pos): Collection
    {
        $types = trim(Reader::parseString($buf, $pos), ',');
        if (strlen($types) == 0) return new Collection();
        $types = collect(str_split($types))->filter(function($type) {
            return in_array($type, [Identifier::$INT, Identifier::$FLOAT, Identifier::$STRING, Identifier::$BLOB]);
        });
        return $types->map(function($type) use ($buf, &$pos){
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
                    return null;
                    break;
            }
        });
    }
}
