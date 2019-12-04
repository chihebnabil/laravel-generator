<?php


namespace Chiheb\Generator\Helpers;


use Illuminate\Support\Str;

class Route
{

    static  function routesAsString($class,$type){
       if($type == "web"){
           $namespace  = "App\Http\Controllers";
       }else{
           $namespace  = "App\Http\Controllers\Api";
       }

       $path = Str::lower(Str::plural($class));
       return 'Route::resource('."'/{$path}'". ' , '.$class.'Controller::class) ;';

    }

    static function addWebRoute($class){
        $newRoutesAsString = self::routesAsString($class,'web');
        file_put_contents( base_path('routes/web.php'), $newRoutesAsString, FILE_APPEND );
    }

    static function addApiRoute($class){
        $newRoutesAsString = self::routesAsString($class,'api');
        file_put_contents( base_path('routes/api.php'), $newRoutesAsString, FILE_APPEND );
    }
}