<?php

namespace NinjaMapper\Tests\Converter;

use NinjaMapper\Converter\StringConverter;

use NinjaMapper\Tests\TestCase;
class StringConverterTest extends TestCase {
    /**
     * @dataProvider getConvertersData
     */
    public function testConverterTakesArrayOfValuesAndJoinsThemTogetherOnSeperator($expected, $matches, $config = array(), $key = null) {
        $converter = $this->getConverter();
        $this->assertEquals($converter->standardise($matches, $config, $key), $expected);
    }

    public function getConvertersData() {
        return array(
            array('TestData', array('Test', 'Data')),
            array('Test, Data', array('Test', 'Data'), array('seperator' => ', ')),
            array('TestData', array(' Test ', ' Data ')),
            array('TestData', array('Test ', ' Data')),
            array('Test Data', array('Test', ' Data'), array('seperator' => '  '))
        );
    }

    protected function getConverter() {
        return new StringConverter();
    }
}