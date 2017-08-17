<?php

namespace gnaritasinc\datapackage2sql;

class WPSQLGenerator extends BaseSQLGenerator
{
    protected function quoteArray ($arr) 
    {
        $format = $this->getFormatString('%s', count($arr));
        return $this->dbh->prepare($format, $arr);
    }

    protected function validateIdentifier ($col) 
    {
        if (preg_match('/[^0-9A-Za-z_-]/', $col)) {
            throw new Exception("Invalid identifier: '$col'. Use only letters, numbers, underscores or hyphens in table or column names.");
        }
    }
}
