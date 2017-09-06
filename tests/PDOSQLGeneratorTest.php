<?php

use gnaritasinc\datapackage2sql\tests\BaseTestCase;
use gnaritasinc\datapackage2sql\PDOSQLGenerator;

class PDOSQLGeneratorTest extends BaseTestCase 
{
    protected static $dbh;
    protected $sqlGenerator;

    public static function setUpBeforeClass()
    {   
        $dsn = sprintf("mysql:dbname=%s;host=%s;charset=%s", DB_SCHEMA, DB_HOST, DB_ENCODING);
        self::$dbh = new PDO($dsn, DB_USER, DB_PASS);
    }

    public static function tearDownAfterClass()
    {
        self::$dbh = null;
    }

    protected function setUp ()
    {
        $this->sqlGenerator = new PDOSQLGenerator(self::$dbh, $this->tablePrefix);
    }

}
