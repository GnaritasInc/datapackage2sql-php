<?php

namespace gnaritasinc\datapackage2sql\tests;

use PHPUnit\Framework\TestCase;
use gnaritasinc\datapackage2sql\tests\MockResource;

class BaseTestCase extends TestCase
{

    protected $tablePrefix = "test_";
    protected $fixtureDir;
    protected $resourceDir;

    public function __construct ()
    {
        $this->fixtureDir = dirname(__FILE__) . "/fixtures";
        $this->resourceDir = $this->fixtureDir . "/resources";
    }
   
    public function testForeignKey ()
    {
        $this->checkResourceSQL("foreign-key");
    }

    protected function checkResourceSQL ($baseFilename)
    {
        $sql = $this->getResourceTableSQL($this->resourceDir."/$baseFilename.json");
        $this->assertSqlEqualsFile($this->resourceDir."/$baseFilename.sql", $sql);        
    }

    protected function assertSqlEqualsFile ($expectedFilename, $actualSql)
    {
        $expectedSql = file_get_contents($expectedFilename);
        $this->assertEquals($this->normalizeSpace($expectedSql), $this->normalizeSpace($actualSql));
    }

    protected function normalizeSpace ($str)
    {
        $str = trim($str);        
        $str = preg_replace('/\s+/', ' ', $str);

        return $str;
    }

    protected function getResourceTableSQL ($jsonFile)
    {
        $resource = $this->getMockResource($jsonFile);
        return $this->sqlGenerator->getTableSQL($resource);
    }

    protected function getMockResource ($jsonFile)
    {
        $json = file_get_contents($jsonFile);
        return new MockResource($json);
    }
}
