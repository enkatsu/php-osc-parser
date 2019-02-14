<?php

namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;
use Reader;

class Identifier
{
    public static $Bundle = '#bundle';
    public static $Int    = 'i';
    public static $Float  = 'f';
    public static $String = 's';
    public static $Blob   = 'b';
}

class Parser
{
    private $messages = new Collection();
    public function parse(Collection $buf, int &$pos, int $endPos, ulong $timestamp=0x1u)
    {
        $first = Reader::parseString($buf, $pos);
        if ($first == Identifier::$Bundle)
        {
            // ParseBundle(buf, ref pos, endPos);
        } else
        {
            $values = ParseData(buf, ref pos);
            // lock (lockObject_)
            // {
            //     messages_.Enqueue(new Message()
            //     {
            //         address = first,
            //         timestamp = new Timestamp(timestamp),
            //         values = values
            //     });
            // }
        }

        if ($pos != $endPos)
        {
            // Debug.LogErrorFormat(
            //     "The parsed data size is inconsitent with the given size: {0} / {1}",
            //     pos,
            //     endPos);
        }
    }

    private function parseBundle(Collection $buf, int &$pos, int $endPos)
    {
        $time = Reader::parseTimetag($buf, $pos);
        while ($pos < $endPos)
        {
            $contentSize = Reader::parseInt($buf, $pos);
            // if (Util.IsMultipleOfFour(contentSize))
            // {
            //     Parse(buf, ref pos, pos + contentSize, time);
            // }
            // else
            // {
            //     Debug.LogErrorFormat("Given data is invalid (bundle size ({0}) is not a multiple of 4).", contentSize);
            //     pos += contentSize;
            // }
        }
    }
}
