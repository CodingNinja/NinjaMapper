<?php

namespace NinjaMapper\Tests\Converter;

use NinjaMapper\Tests\TestCase;
abstract class ConverterTest extends TestCase {

    public abstract function getConverter();

    public abstract function getConvertersData();

}