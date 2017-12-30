<?php

namespace gnaritasinc\datapackage2sql;

use gnaritasinc\datapackage2sql\Exceptions\MalformedIdentifierException;

abstract class BaseSQLGenerator
{
    public static $MAX_KEY_LEN = 255;

    protected $tablePrefix = "";
    protected $dbh;

    public function __construct (&$dbh, $tablePrefix="")
    {
        $this->dbh = $dbh;
        $this->tablePrefix = $tablePrefix;
    }

    public function getTableSQL ($resource)
    {


        $output = "";
        $output .= "create table " . $this->getTableRef($resource->descriptor()->name) . " (";
        $output .= "\n\t" . implode($this->getColumnDefs($resource->descriptor()->schema), ",\n\t");
        $output .= "\n);\n";

        return $output;
    }

    protected function getTableRef ($name)
    {
        return $this->quoteNames($this->tablePrefix . $name);
    }

    protected function getFieldConstraints ($field)
    {
        $constraints = array();
        if (!property_exists ($field , "constraints" )) {
            return "";
        }
        if ($field->constraints->required) {
            $constraints[] = "not null";
        }
        if ($field->constraints->unique) {
            $constraints[] = "unique";
        }

        return implode(" ", $constraints);
    }

    protected function sqlDataType ($field, $schema)
    {
        $types = array(
            "string" =>"text",
            "number" =>"decimal",
            "integer" =>"int",
            "boolean" =>"varchar(5)",
            "date" =>"date",
            "time" =>"time",
            "datetime" =>"datetime"
        );

        if ($this->isEnum($field)) {
            return $this->getEnumDef($field);
        }

        if ($field->type == "string" && $this->isKeyColumn($field, $schema)) {
            return sprintf("varchar(%d)", self::$MAX_KEY_LEN);
        }

        if($field->type == "string" && property_exists ($field , "constraints" ) && in_array("maxLength", $field->constraints)) {
            return sprintf("varchar(%d)", $field->constraints->maxLength);
        }

        return array_key_exists($field->type, $types) ? $types[$field->type] : "text";
    }


    protected function isKeyColumn ($field, $schema)
    {
        return $this->isPrimaryKey($field, $schema) || $this->isForeignKey($field, $schema) || $this->isUniqueKey($field);
    }

    protected function isUniqueKey ($field)
    {
        return property_exists ($field , "constraints" ) && $field->constraints->unique;
    }

    protected function isPrimaryKey ($field, $schema)
    {
        if (!$schema->primaryKey) return false;
        $fieldName = $field->name;
        $primaryKeys = $this->getArray($schema->primaryKey);
        return in_array($fieldName, $primaryKeys);
    }

    protected function isForeignKey ($field, $schema)
    {
        if (!property_exists ($schema , "foreignKeys" )) return false;
        $fieldName = $field->name;
        $fkCols = array();
        foreach($schema->foreignKeys as $fk) {
            $fkCols =  array_merge($fkCols, $this->getArray($fk->fields));
        };

        return in_array($fieldName, $fkCols);
    }

    protected function isEnum ($field)
    {
        return (property_exists ($field , "constraints" ) && $field->constraints->enum && count($field->constraints->enum)) ? true : false;
    }

    function getEnumDef ($field) {
        return " enum(". $this->quoteArray($field->constraints->enum) .")";
    }

    protected function getColumnDefs  ($schema)
    {
        $defs = array();
        foreach($schema->fields as $field) {
            $this->validateIdentifier($field->name);
            $def = sprintf("%s ", $this->quoteIdentifier($field->name));
            $def .= $this->sqlDataType($field, $schema);
            $constraints = $this->getFieldConstraints($field);
            if ($constraints) {
                $def .= " " . $constraints;
            }
            $defs[] = $def;
        }

        if ($schema->primaryKey) {
            $cols = $this->getArray($schema->primaryKey);
            $defs[] = "primary key (". $this->quoteNames($cols) .")";
        }

        if (property_exists ($schema , "foreignKeys" )) {
            foreach($schema->foreignKeys as $fk) {
                $fkCols = $this->getArray($fk->fields);
                $def = "foreign key(". $this->quoteNames($fkCols) .")";
                $def .= " references " . $this->getTableRef($fk->reference->resource);
                $def .= " (" . $this->quoteNames($this->getArray($fk->reference->fields)) . ")";
                $defs[] = $def;
            };
        }

        return $defs;
    }

    protected function getArray ($val)
    {
        return is_array($val) ? $val : array($val);
    }

    protected function quoteNames ($cols)
    {
        $cols = $this->getArray($cols);
        $this->validateColNames($cols);
        $quotedCols = array();

        foreach ($cols as $col) {
            $quotedCols[] = $this->quoteIdentifier($col);
        }
        $format = $this->getFormatString('%s', count($quotedCols));

        return vsprintf($format, $quotedCols);
    }

    protected function getFormatString ($format, $length, $delimiter=", ")
    {
        return implode($delimiter, array_fill(0, $length, $format));
    }

    protected function validateColNames ($cols)
    {
        foreach ($cols as $col) {
            $this->validateIdentifier($col);
        }
    }

    protected function quoteIdentifier ($field)
    {
        return "`". str_replace("`", "``", $field). "`";
    }

    protected function validateIdentifier ($col)
    {
        if (preg_match('/[^0-9A-Za-z_-]/', $col)) {
            throw new MalformedIdentifierException("Invalid identifier: '$col'. Use only letters, numbers, underscores or hyphens in table or column names.");
        }
    }

    abstract protected function quoteArray ($arr); // for enum column values

}
