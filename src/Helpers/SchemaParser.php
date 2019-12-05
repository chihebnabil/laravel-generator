<?php


namespace Chiheb\Generator\Helpers;


class SchemaParser
{

    public static function parseFields($fields)
    {
        $fieldsAsArray  = explode(',',$fields);
        $schema = "";

        foreach ($fieldsAsArray as $k => $v ){
            $t = explode(':', $v);
            $schema .=  '$'.'table'.'->'.trim($t[1]).'("'.trim($t[0]).'") ;'. PHP_EOL;
        }
       return $schema;


    }
}