<?php

declare(strict_types=1);

namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Reader
{
    public static function parseString(Collection $buf, int &$pos): string
    {
        $lastIndex = $buf->slice($pos)->search(function($data) {
            $bytes = collect(str_split($data, 2));
            return $bytes->last() == '00';
        });
        $len = $lastIndex - $pos + 1;
        $val = \pack('H*', $buf->slice($pos, $len)->implode(''));
        $pos += $len;
        return \trim($val);
    }

    public static function parseInt(Collection $buf, int &$pos): int
    {
        $val = \hexdec($buf->get($pos));
        $pos += 1;
        return $val;
    }

    public static function ParseFloat(Collection $buf, int &$pos)
    {
        $str = $buf->get($pos);
        $value = unpack("f", pack("h*", strrev($str)))[1];
        $pos += 1;
        return $value;
    }

    // public static byte[] ParseBlob(byte[] buf, ref int pos)
    // {
    //     var size = ParseInt(buf, ref pos);
    //     var value = new byte[size];
    //     Buffer.BlockCopy(buf, pos, value, 0, size);
    //     pos += Util.GetBufferAlignedSize(size);
    //     return value;
    // }

    public static function parseTimetag(Collection $buf, int &$pos): int
    {
        $val = \hexdec($buf->slice($pos, 2)->flatten()->implode(''));
        $pos += 2;
        return $val;
    }
}
