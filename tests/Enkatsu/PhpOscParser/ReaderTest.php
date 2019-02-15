<?php
namespace Enkatsu\PhpOscParser\Tests;

use Enkatsu\PhpOscParser\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testStringRead()
    {
        $hello = 'Hello';
        $hex = \join(\array_map(dechex, \array_map(ord, \str_split($hello))));
        $buf = \collect(\array_chunk(\str_split($hex), 2));
        $pos = 0;
        $result = Reader::parseString($buf, $pos);
        $this->assertEquals($hello, $result);
        $this->assertEquals($pos, $buf->count());
    }

    public function testIntRead()
    {
        $num = 2147483647;
        $hex = \dechex($num);
        $buf = \collect(\array_chunk(str_split($hex), 2));
        $pos = 0;
        $result = Reader::parseInt($buf, $pos);
        $this->assertEquals($num, $result);
        $this->assertEquals($pos, $buf->count());
    }

    public function testFloatRead()
    {
        $hex = '40200000';
        $num = unpack('f', pack('h*', strrev($hex)));
        $buf = \collect(\array_chunk(str_split($hex), 2));
        $pos = 0;
        $result = Reader::parseFloat($buf, $pos);
        $this->assertEquals($num, $result);
        $this->assertEquals($pos, $buf->count());
    }
}
