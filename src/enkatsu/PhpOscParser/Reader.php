<?php

declare(strict_types=1);

namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Reader
{

  public static function parseString(Collection $buf, int &$pos): string
  {
    $size = 0;
    $bufSize = $buf->count();
    for (; hexdec($buf->get($pos + $size)) != 0; ++$size);
    $val = hex2bin($buf->slice($pos, $size)->flatten()->implode(''));
    $pos += $size;
    return $val;
    }

    public static function parseInt(Collection $buf, int &$pos): int
    {
      $val = \hexdec($buf->slice($pos, 4));
      $pos += 4;
      return $val;
    }

    public static function parseTimetag(Collection $buf, int &$pos): int
    {
      // TODO:
      $val = \hexdec($buf->slice($pos, 8));
      $pos += 8;
      return $value;
    }

    // TODO:
    // public static float ParseFloat(byte[] buf, ref int pos)
    // {
    //     Array.Reverse(buf, pos, 4);
    //     var value = BitConverter.ToSingle(buf, pos);
    //     pos += 4;
    //     return value;
    // }
    //
    // public static byte[] ParseBlob(byte[] buf, ref int pos)
    // {
    //     var size = ParseInt(buf, ref pos);
    //     var value = new byte[size];
    //     Buffer.BlockCopy(buf, pos, value, 0, size);
    //     pos += Util.GetBufferAlignedSize(size);
    //     return value;
    // }
}
