<?php


namespace Chiheb\Generator\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeControllerCommand extends Command
{
    protected $signature = 'generator:make-controller {name?}  {type=Controller}';


    protected $description = 'Generate Laravel Module from a YAML file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
    }

    private function makeController($class)
    {
        $modelTemplate =
            str_replace(
                ['{{class}}','{{name}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                Helpers\Stub::get('Controller')
            );
        file_put_contents(app_path("/Http/Controllers/{$class}Controller.php"), $modelTemplate);
        if($this->confirm('Do you wish to generate a web route  for this controller ? (yes|no)[no]')) {
            Helpers\Route::addWebRoute($class);
        }
    }
    private function makeDataTableController($class)
    {
        $this->line('Your are required to install yajra datatable package : yajra/laravel-datatables');

        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                Helpers\Stub::get('DataTableController')
            );
        file_put_contents(app_path("/Http/Controllers/{$class}Controller.php"), $modelTemplate);
        if($this->confirm('Do you wish to generate a web route  for this controller ? (yes|no)[no]')) {
            Helpers\Route::addWebRoute($class);
        }
    }
    private function makeApiController($class)
    {
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                Helpers\Stub::get('ApiController')
            );
        if (!file_exists(app_path("/Http/Controllers/Api") )) {
            mkdir(app_path("/Http/Controllers/Api"), 0644, true);
        }
        file_put_contents(app_path("/Http/Controllers/Api/{$class}Controller.php"), $modelTemplate);
        if($this->confirm('Do you wish to generate an api route  for this controller ? (yes|no)[no]')) {
            Helpers\Route::addApiRoute($class);
        }
    }



}