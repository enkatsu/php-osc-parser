<?php
namespace Enkatsu\PhpOscParser;

use Tightenco\Collect\Support\Collection;

class Bundle implements ElementInterface
{
    public $timetag;
    public $elements;

    function __construct(int $timetag)
    {
        $this->elements = new Collection();
    }

    function clear(): void
    {
        $this->timetag = 1;
        $this->elements = new Collection();
    }

    function push(ElementInterface $element): void
    {
        $klass = get_class($element);
        if($klass == Bundle::class)
        {
            $this->elements->push($element);
        } else if ($klass == Message::class)
        {
            $this->elements->put($element->address, $element);
        }
    }

    function getElements(): Collection
    {
        return $this->elements;
    }

    function getElement($address): ?ElementInterface
    {
        return $this->elements->get($address);
    }
}
