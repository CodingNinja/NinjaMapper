<?php
namespace Ninja\Mapper\Mapper\Map;


use Ninja\Mapper\Mapper\Map;

class IteratorMap extends Map implements \ArrayAccess, \IteratorAggregate
{
    public function getIterator() {
        return new MapIterator($this->getMap());
    }

}