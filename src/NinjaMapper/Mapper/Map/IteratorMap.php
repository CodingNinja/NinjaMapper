<?php
namespace NinjaMapper\Mapper\Map;


use NinjaMapper\Mapper\Map;

class IteratorMap extends Map implements \ArrayAccess, \IteratorAggregate
{
    public function getIterator() {
        return new MapIterator($this->getMap());
    }

}