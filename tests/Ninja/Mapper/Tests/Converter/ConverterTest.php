<?php

namespace Ninja\Mapper\Tests\Converter;

use Ninja\Mapper\Tests\TestCase;
abstract class ConverterTest extends TestCase {

    public abstract function getConverter();

    public abstract function getConvertersData();

}