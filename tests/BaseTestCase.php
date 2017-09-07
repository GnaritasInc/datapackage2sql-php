<?php

namespace gnaritasinc\datapackage2sql\tests;

use PHPUnit\Framework\TestCase;
use gnaritasinc\datapackage2sql\tests\MockResource;
use gnaritasinc\datapackage2sql\Exceptions\MalformedIdentifierException;

class BaseTestCase extends TestCase
{

    protected $tablePrefix = "test_";
   
    /**
     *  @dataProvider resourceProvider
     */
    public function testGetTableSQL ($baseFilename)
    {
        $resourceDir = dirname(__FILE__)."/fixtures/resources";
        $sql = $this->getResourceTableSQL($resourceDir."/$baseFilename.json");
        $this->assertSqlEqualsFile($resourceDir."/$baseFilename.sql", $sql);        
    }

    public function resourceProvider () 
    {
       
        $resourceFiles = array();
        $dh = opendir(__DIR__."/fixtures/resources/");
        while(false !== ($entry = readdir($dh))) {
            if ($entry != "." && $entry != "..") {
                $baseFilename = substr($entry, 0, strpos($entry, '.'));
                if (!array_key_exists($baseFilename, $resourceFiles)) {
                    $resourceFiles[$baseFilename] = array($baseFilename);
                }
            }
        }

        return $resourceFiles;
        
    }

    public function testMalformedIdentifier () 
    {
        $this->expectException(MalformedIdentifierException::class);
        $descriptor = array("name"=>"Bad table ``` name");
        $resource = new MockResource(json_encode($descriptor));
        $this->sqlGenerator->getTableSQL($resource);
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
