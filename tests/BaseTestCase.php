<?php

namespace gnaritasinc\datapackage2sql\tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{

    protected $tablePrefix = "test_";
   
    public function testDummy ()
    {
        $this->assertEquals("foo", "foo");
    }
}
