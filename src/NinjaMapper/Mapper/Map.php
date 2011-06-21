<?php

namespace NinjaMapper\Mapper;


class Map implements \ArrayAccess, \IteratorAggregate
{
    public static $converters = array(
        self::CONVERTER_STANDARDISER => array('\NinjaMapper\Mapper\Map\Standardiser\MapBasicStandardiser'),
        self::CONVERTER_STRING => array('\NinjaMapper\Converter\StringConverter')
    );

    const CONVERTER_STANDARDISER = 0;

    const CONVERTER_STRING = 1;

    protected $map;

    public function setMap($map) {
        $this->map = $map;
    }

    public function getMap() {
        return $this->map;
    }

    protected function standardise($map) {
        $map = $this->convert($map, $this->getConverters(self::CONVERTER_STANDARDISER));

        return $map;
    }

    protected function convert($value, array $converters, $key = null) {
        foreach($converters as $converter) {
            $value = $converter->standardise($value, $converters, $key);
        }

        return $value;
    }

    protected function getConverters($converterTypes) {
        $converterTypes = (array) $converterTypes;
        foreach($converterTypes as $converterType) {
            $converters = isset(self::$converters[$converterType]) ? self::$converters[$converterType] : array();
            $ret = array();
            foreach($converters as $i=>$converter) {
                if(is_string($converter)) {
                    self::$converters[$converter][$i] = new $converter();
                }

                $ret[] = self::$converters[$converter][$i];
            }
        }
        return $ret;
    }

    public function remap($config, $row, $key) {
        $converters = (array) $this->getConverters($config['type']);
        $values = array();

        $values = $this->getMatchedValues($config['match'], $row, $config);

        $data = $this->convert($values, $converters, $key);

        return $data;
    }

    protected function getMatchedValues($match, $row, $config) {
        $values = array();
        foreach($match as $key) {
            if(!isset($row[$key])) {
                throw new \InvalidArgumentException(sprintf('Unable to find matching data key "%s"', key));
            }


            $values[(isset($config['matchMap']) && isset($config['matchMap'][$key])) ? $config['matchMap'][$key] : $key] = $row[$key];
        }

        return $values;

    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset) {
        return $this->exists($offset);
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) {
        return $this->set($offset, $value);
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) {
        return $this->remove($offset);
    }

    public function exists($offset) {
        return isset($this->map[$offset]);
    }

    public function get($offset) {
        return $this->map[$offset];
    }

    public function set($offset, $value) {
        if(!isset($offset) || !$offset) {
            throw new InvalidArgumentException('You must specify an offset when adding to a map');
        }

        $this->map[$offset] = $value;

        return $this;
    }

    public function remove($offset) {
        unset($this->map[$offset]);
    }

    public function getIterator() {
        return new \ArrayIterator((array) $this->standardise($this->map, $this->getConverters(self::CONVERTER_STANDARDISER)));
    }
}