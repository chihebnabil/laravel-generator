<?php


namespace Chiheb\Generator\Helpers;


class Stub
{

    public static function get($type)
    {
        switch ($type){
            case 'Model':
                return file_get_contents(__DIR__ . '/../stubs/model.stub');
                break;
            case 'Controller':
                return file_get_contents(__DIR__ . '/../stubs/controller.stub');
            case 'DataTableController':
                return file_get_contents(__DIR__ . '/../stubs/controller_datatable.stub');
            case 'ApiController':
                return file_get_contents(__DIR__ . '/../stubs/controller_api.stub');
                break;
            case 'Migration':
                return file_get_contents(__DIR__ . '/../stubs/migration.stub');
                break;
            case 'ViewTable':
                return file_get_contents(__DIR__ .'/../stubs/vue/table.stub');
                break;
        }
    }
}