<?php

require_once __DIR__ . '../../../kurt/vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'NinjaMapper\\Tests'   => '../',
    'NinjaMapper'          => dirname(dirname(__DIR__)).'/src/',
    'Symfony'              => '../../kurt/vendor/symfony/src/Symfony/src'
));


$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));

$loader->register();