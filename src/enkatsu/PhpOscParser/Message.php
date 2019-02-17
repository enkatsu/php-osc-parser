<?php
namespace Enkatsu\PhpOscParser;

class Message {
    public $address;
    public $timestamp;
    public $values;

    function __construct($address, $timestamp, $values) {
        $this->address = $address;
        $this->timestamp = $timestamp;
        $this->values = $values;
    }
}
