<?php

require_once __DIR__ . '/vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Ninja\Mapper\Tests'   => __DIR__.'/tests',
    'Ninja\\Mapper'          => __DIR__ .'/src/',
    'Symfony'              => __DIR__ . '/vendor/symfony/src/Symfony/src'
));


$loader->registerNamespaceFallbacks(array(
    __DIR__.'/tests',
));

$loader->register();