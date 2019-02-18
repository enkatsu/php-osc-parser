<?php
namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Bundle implements ElementInterface
{
    public $timetag;
    public $elements;

    function __construct($timetag)
    {
        $this->elements = new Collection();
    }

    function clear(): void
    {
        $this->timetag = 1;
        $this->elements = new Collection();
    }

    function push(ElementInterface $element)
    {
        $this->elements->push($element);
    }

    function getElements(): Collection
    {
        return $this->elements;
    }
}
