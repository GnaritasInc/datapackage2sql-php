<?php

namespace gnaritasinc\datapackage2sql\tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{

    public function testDummy ()
    {
        $this->assertEquals("foo", "foo");
    }
}
