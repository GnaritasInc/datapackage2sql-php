<?php

namespace gnaritasinc\datapackage2sql;

class WPSQLGenerator extends BaseSQLGenerator
{
    protected function quoteArray ($arr) 
    {
        $format = $this->getFormatString('%s', count($arr));
        return $this->dbh->prepare($format, $arr);
    }    
}
