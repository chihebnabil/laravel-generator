<?php


namespace Chiheb\Generator\Helpers;


class Functions
{


    public static function parseCommaSeparatedStr($fields)
    {
        $fieldsAsArray  = explode(',',$fields);
        $str = "";

        foreach ($fieldsAsArray as $f){
            $f = trim($f);
            $str .= "\"";
            $str .=  $f ;
            $str .= "\" ";
            $str .= ',';
        }

        return $str;
    }
}