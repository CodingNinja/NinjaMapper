<?php

namespace Ninja\Mapper\Mapper\Map;

abstract class Standardiser {

    public function standardise($map) {
        return $this->doStandardise($map);
    }

    public function standardiseSingle($map) {
        return $this->doStandardiseMapDefinition($definition);($map);
    }

    protected function doStandardise($map) {
        foreach($map as $key => $definition) {
            $map[$key] = $this->doStandardiseMapDefinition($definition);
        }

        return $map;
    }

    protected abstract function doStandardiseMapDefinition($definition);
}