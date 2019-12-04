<?php


namespace Chiheb\Generator\Helpers;


use Illuminate\Support\Str;

class Migration
{

    /**
     * @param $class
     * @return string
     */
    public static function getFilename($class)
    {
        $className = Self::getTableName($class);
        return  date('Y_m_d_His_')."_create_{$className}_table.php";
    }

    /**
     * @param $class
     * @return string
     */
    public static function getTableName($class){
        return Str::lower(Str::plural($class));
    }

    public static function getName($class){
        return Str::plural($class);
    }

}