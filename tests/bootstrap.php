<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once __DIR__.'/../vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('gnaritasinc\\datapackage2sql\\tests\\', __DIR__, true);
$classLoader->register();
