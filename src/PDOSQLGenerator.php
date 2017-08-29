<?php

namespace gnaritasinc\datapackage2sql;

class PDOSQLGenerator extends BaseSQLGenerator 
{
    protected function quoteArray ($arr) 
    {
        $quotedValues = array();
        foreach ($arr as $val) {
            $quotedValues[] = $this->dbh->quote($val);
        }

        $format = $this->getFormatString('%s', count($arr));
        return vsprintf($format, $quotedValues);
    }
}