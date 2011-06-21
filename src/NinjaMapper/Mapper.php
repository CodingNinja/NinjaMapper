<?php

namespace NinjaMapper;

use NinjaMapper\Mapper\Map;

use NinjaMapper\Mapper;

class Mapper implements \ArrayAccess, \IteratorAggregate {

    private $options = array();

    private $baseMap = array(
        'renderer' => '\NinjaMapper\Converter\String',
        'matchMap' => array()
    );

    public function __construct(array $config = array()) {
        $this->setConfig($config);
    }

    /**
     * Set Configuration options and attempt to call proper
     * setter fnctions where possible.
     *
     * <code>
     *		<?php
     *		$mapper = new Mapper();
     *		$mapper->setConfig('datafile' => '/path/to/my/data/file');
     * </code>
     *
     * In this example, because there is a {@link Mapper::setDataFile()} method, this will be
     * called.
     *
     * @param array|Traversable A configuration array or a key when specifiying a $value
     * @param mixed The configuration option's value
     * @return \NinjaMapper\Mapper
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function setConfig($config, $value = null) {
        if($value !== null) {
            $config = array($config => $value);
        }

        foreach($config as $key => $value) {
            $this->{'set' . $key}($value);
        }

        return $this;
    }

    /**
     * @param unknown_type $key
     * @return Ambiguous
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function getConfig($key = null) {
        if(null === $key) {
            return $this->options;
        }

        return $this->{'get' . $key}();
    }

    /**
     * Enter description here ...
     * @param unknown_type $file
     * @param unknown_type $type
     * @return \NinjaMapper\Mapper
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function setMapFile($file, $type = null) {
        $reader = (null === $type) ? $this->getReaderForFile($file) : $this->getReaderForType($type);

        $fileData = $this->doFileLoad($file);

        $this->setMapArray($reader->parse($fileData));

        return $this;
    }

    /**
     * Enter description here ...
     * @param unknown_type $file
     * @throws \InvalidArgumentException
     * @return string
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function doFileLoad($file) {
        if(!file_exists($file) || !is_readable($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exist or is not readable',  $file));
        }

        return file_get_contents($file);
    }

    /**
     * Enter description here ...
     * @param unknown_type $file
     * @param unknown_type $typeJ
     * @return \NinjaMapper\Reader\CSV
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    protected function getReaderForFile($file, $typeJ = null) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        return $this->getReaderForType($extension);
    }

    /**
     * Enter description here ...
     * @param unknown_type $ext
     * @return \NinjaMapper\Reader\CSV
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    protected function getReaderForType($ext) {
        return new \NinjaMapper\Mapper\XMLMapper();
    }

    /**
     * Enter description here ...
     * @param unknown_type $file
     * @param unknown_type $type
     * @return \NinjaMapper\Mapper
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function setDataFile($file, $type = null) {
        $fileData = $this->doFileLoad($file);

        $reader = (null === $type) ? $this->getReaderForFile($file) : $this->getReaderForType($type);

        $reader->read($file);

        $this->setDataArray($data);

        return $this;
    }

    /**
     * Enter description here ...
     * @param unknown_type $array
     * @return \NinjaMapper\Mapper
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function setMapArray($array) {
        $this->map = new Map($this);
        $this->map->setMap($array);

        return $this;
    }

    /**
     * Enter description here ...
     * @param unknown_type $array
     * @return \NinjaMapper\Mapper
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function setDataArray($array) {
        $this->data = $array;

        return $this;
    }

    /**
     * Enter description here ...
     * @return Ambigous <\NinjaMapper\Ambigous, unknown_type, multitype:, multitype:unknown >
     */
    public function getMap() {
        return $this->map;
    }

    /**
     * Enter description here ...
     * @return unknown_type
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Enter description here ...
     * @return Ambigous <boolean, unknown>
     */
    public function map() {
        return $this->doMap();
    }

    /**
     * Enter description here ...
     * @return boolean|unknown
     */
    private function doMap() {
        $data = $this->beforeMap();

        if(false === $data) {
            return false;
        }elseif(!is_array($data) && !$data instanceof \Traversable) {
            $data = array();
        }

        foreach($this->data as $row) {
            $data[] = $this->doRemap($row);
        }

        $after = $this->afterMap();
        if(is_array($after) || $after instanceof \Traversable) {
            $data = $after;
        }

        return $data;
    }

    protected function doRemap($row) {
        $data = array();
        foreach($this->map as $key => $config) {
            $data[$key] = $this->map->remap($config, $row, $key);
        }

        return $data;
    }

    protected function beforeMap(){}

    protected function afterMap(){}


    /**
     * Magic Getter / Setter Functions
     *
     * @param string The method name
     * @param array An array of arguments
     * @throws \BadMethodCallException Thrown when not a "getter" or "setter" method.
     * @return \NinjaMapper\Mapper|multitype:
     *
     * @author David Mann <ninja@codingninja.com.au>
     */
    public function __call($m, $a) {
        $verb = substr($m, 0, 3);
        if($verb === 'set') {
            $this->options[strtolower($m[3]).substr($m, 4)] = $a[0];
            return $this;
        }elseif($verb === 'get') {
            return $this->options[strtolower($m[3]).substr($m, 4)];
        }
        throw new \BadMethodCallException(sprintf('No such method "%s"', $m));
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset) {
        return method_exists($this, 'set' . ucfirst($offset)) || method_exists($this, 'get' . ucfirst($offset));
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) {
        return $this->{'get' . ucfirst($offset)}();
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) {
        if(!$offset) {
            throw new \InvalidArgumentException(sprintf('Invalid offset "%s"', $offset));
        }

        return $this->{'set' . ucfirst($offset)}($value);
    }

	/* (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) {
        return $this->{'set' . ucfirst($offset)}(false);
    }

	/* (non-PHPdoc)
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }
}