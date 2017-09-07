<?php

namespace gnaritasinc\datapackage2sql\tests;

class MockResource
{
    private $descriptor;
    
    public function __construct ($jsonDescriptor) 
    {
        $this->descriptor = json_decode($jsonDescriptor);
    }

    public function descriptor ()
    {
        return $this->descriptor;
    }
}
