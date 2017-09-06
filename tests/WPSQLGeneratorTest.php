<?php

use gnaritasinc\datapackage2sql\tests\BaseTestCase;
use gnaritasinc\datapackage2sql\WPSQLGenerator;

class WPSQLGeneratorTest extends BaseTestCase 
{
    protected static $dbh;
    protected $sqlGenerator;

    public static function setUpBeforeClass()
    {   
        
        self::$dbh = new ezSQL_mysqli(DB_USER, DB_PASS, DB_SCHEMA, DB_HOST, DB_ENCODING);
    }

    public static function tearDownAfterClass()
    {
        self::$dbh = null;
    }

    protected function setUp ()
    {
        $this->sqlGenerator = new WPSQLGenerator(self::$dbh, $this->tablePrefix);
    }
}