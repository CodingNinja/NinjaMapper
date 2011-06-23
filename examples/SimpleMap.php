<?php

namespace Ninja\Mapper\Examples;
include __DIR__ . '/../tests/bootstrap.php';

use Ninja\Mapper\Mapper;

$mapper = new Mapper();
$data = array();

$mapper->setDataArray(array(
    array('first_name' => 'Test', 'middle_name' => 'Reece', 'last_name' => 'Mann'),
    array('first_name' => 'David', 'middle_name' => 'David', 'last_name' => 'Reece'),
    array('first_name' => 'Reece', 'middle_name' => 'Mann', 'last_name' => 'Reece')
));

$mapper->setMapFile(__DIR__ . '/data/SimpleMap.xml');

print_r($mapper->map());