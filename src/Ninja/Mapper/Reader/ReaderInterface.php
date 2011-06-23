<?php

namespace Ninja\Mapper\Reader;

interface ReaderInterface {
    public function parse($file);

    public  function read($contents);
}