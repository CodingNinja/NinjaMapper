<?php

namespace NinjaMapper\Reader;

interface ReaderInterface {
    public function parse($file);

    public  function read($contents);
}