<?php

namespace Chiheb\Generator\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\VarDumper\Cloner\Stub;

use Chiheb\Generator\Helpers;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generator:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type  = $this->choice('What do you want to  generate?', ['Model', 'Controller','DataTableController','ApiController','Migration','Crud'], null);
        $class = $this->ask("What it's model class name ? Ex : Post");
        switch ($type){
            case 'Model':
                $this->makeModel($class);
                break;
            case 'Controller':
                $this->makeController($class);
                break;
            case 'DataTableController':
                $this->makeDataTableController($class);
            case 'ApiController':
                $this->makeApiController($class);
                break;
            case 'Migration':
                $this->makeMigration($class);
                break;

            case 'Crud':
                $this->makeModel($class);
                $this->makeDataTableController($class);
                $this->makeTableView($class);
                break;
        }
    }


    private function makeModel($class)
    {
        $fields = $this->ask("Enter you comma separated fields Ex : name , category_id ");
        $modelTemplate =
            str_replace(
                ['{{class}}','{{namespace}}','{{fields}}','{{table}}'],
                [$class,"App\\".$class,$fields,Str::lower(Str::plural($class))],
                Stub::get('Model')

            );
        file_put_contents(app_path("/{$class}.php"), $modelTemplate);
    }

    private function makeController($class)
    {
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                Helpers\Stub::get('Controller')
            );
        file_put_contents(app_path("/Http/Controllers/{$class}Controller.php"), $modelTemplate);
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
            mkdir(app_path("/Http/Controllers/Api"), 0777, true);
            file_put_contents(app_path("/Http/Controllers/Api/{$class}Controller.php"), $modelTemplate);

        }
    }

    private function makeMigration($class)
    {
        $fields = $this->ask("Enter you comma separated fields:type Ex : name:string ");
        $fieldsAsArray  = explode(',',$fields);
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}','{{fields}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class)),$fieldsAsArray],
                Helpers\Stub::get('Migration')
            );
        file_put_contents(database_path("/migrations/create_{$class}_table.php"), $modelTemplate);
    }
}
