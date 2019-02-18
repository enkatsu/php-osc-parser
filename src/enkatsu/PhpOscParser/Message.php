<?php
namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Message implements ElementInterface
{
    public $address;
    public $timestamp;
    public $values;

    function __construct(string $address, int $timestamp, Collection $values)
    {
        $this->address = $address;
        $this->timestamp = $timestamp;
        $this->values = $values;
    }
}
