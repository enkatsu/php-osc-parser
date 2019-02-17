<?php
namespace Enkatsu\PhpOscParser\Tests;
use Enkatsu\PhpOscParser\Parser;

class ParseTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $str = '#bundle';
        $parser = new Parser();
    }
}
