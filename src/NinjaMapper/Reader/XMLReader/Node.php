<?php

namespace NinjaMapper\Reader\XMLReader;

class Node implements \ArrayAccess, \IteratorAggregate {
    protected $data = array();

    public function __construct(array $data = array()) {
        $this->data = $data;
    }

    public function getName() {
        return isset($this->data['name']) ? $this->data['name'] : false;
    }

    public function setName($name) {
        $this->data['name'] = $name;

        return $this;
    }

    public function getValue() {
        return isset($this->data['value']) ? $this->data['value'] : false;
    }

    public function setValue($value) {
        $this->data['value'] = $value;

        return $this;
    }

    public function appendValue($value) {
        return $this->setValue($this->getValue() . $value);
    }

    public function getAttributes() {
        return isset($this->data['attributes']) ? $this->data['attributes'] : false;
    }

    public function setAttributes(array $attributes) {
        $this->data['attributes'] = $attributes;

        return $this;
    }

    public function toArray($deep = true, $data = null) {
        if($data === null) {
            $data = $this->data;
        }

        $output = array();
        foreach($data as $key=>$val) {
            if($deep === false && $val instanceof Node) {
                continue;
            }

            $output[$key] = $val instanceof Node ? $val->toArray() : $val;
        }

        return $output;
    }

    public function __toString() {
        return print_r($this->toArray(), true);
    }

    public function getFirst() {
        reset($this->data);
        return $this[key($this->data)];
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        if(!isset($this->data[$offset])) {
            $this->data[$offset] = new Node();
        }

        return $this->data[$offset];
    }

    public function offsetSet($offset, $value) {
        if(!$offset) {
            return $this->data[] = $value;
        }else{
            return $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }
}