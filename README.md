# datapackage2sql-php
PHP library for generating SQL from data packages.

## Installation
* Clone or download this repository
* Run `composer install`

## Usage
This library is intended for use with the Frictionless Data PHP Data Package library: <https://github.com/frictionlessdata/datapackage-php> (required as a dependency of this library).

There are two subclasses of `BaseSQLGenerator` for different database abstractions: one for WordPress' "wpdb" object (`WPSQLGenerator`) and one for PDO (`PDOSQLGenerator`). The generated SQL is intended for use with MySQL.

Assuming you've successfully instantiated a "DataPackage" object, you can generate DDL SQL statements to create the tables defined in the package by instantiating an appropriate "SQLGenerator" for your database abstraction and iterating over the data package's "resources" collection, passing each "resource" to the SQL generator's `getTableSQL()` method.

The generated SQL is intended for use with MySQL.

Here's an example using `WPSQLGenerator`:

```php
global $wpdb;
$datapackage = datapackage\Factory::datapackage($descriptor, $basePath);
$sqlGenerator = new WPSQLGenerator($wpdb, "table_prefix_");

foreach($datapackage->resources() as $resource) {
	$sql = $sqlGenerator->getTableSQL($resource);
	$wpdb->query($sql);
}
```

## Tests
The unit tests require a database connection for proper string escaping. To run the unit tests, edit `phpunit.xml.example` with the connection details for your database and save it as `phpunit.xml`. Then run `composer test` from the package root.

To add additional data fixtures for SQL generation unit tests, add two files for each test to `tests/fixtures/resources`: a `.json` file with the resource definition and a `.sql` file with the same base name containing the expected SQL output.
