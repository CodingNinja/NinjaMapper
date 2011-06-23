<?php

namespace Ninja\Mapper\Converter;

class StringConverter {

    public function standardise($matches, $config, $key) {
        $seperator = isset($config['seperator']) ? $config['seperator'] : '';
        $string = implode($seperator, array_map('trim', $matches));
        return $this->stripExtraWhitespace($string);
    }

    protected function stripExtraWhitespace($string) {
        return preg_replace('/[ ]{2,}/', ' ', $string);
    }
}