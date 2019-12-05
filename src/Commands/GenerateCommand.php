<?php

namespace Chiheb\Generator\Commands;

use Chiheb\Generator\Helpers\SchemaParser;
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
    protected $signature = 'generator:make {type?}  {class?} {fields?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Laravel Models, Controllers ...';

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
        $typeArg = $this->argument("type");
        $classArg = $this->argument("class");
        $fieldsArg = $this->argument("fields");

        if(empty($typeArg)){
            $type  = $this->choice('What do you want to  generate?', [
                'Model', 'Controller','DataTableController','ApiController','Migration','Crud'], null);
                $class = $this->ask("What it's model class name ? Ex : Post");

        }else{
            $type  = $typeArg;
            $class = $classArg;

        }
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
                break;
        }
    }


    private function makeModel($class)
    {
        $fields = $this->ask("Enter you comma separated fields Ex : name , category_id ");
        $modelTemplate =
            str_replace(
                ['{{class}}','{{namespace}}','{{fields}}','{{table}}'],
                [$class,"App\\".$class,Helpers\Functions::parseCommaSeparatedStr($fields),
                    Str::lower(Str::plural($class))],
                Helpers\Stub::get('Model')

            );
        file_put_contents(app_path("/{$class}.php"), $modelTemplate);
        $this->line('Model File Created  : '.$class.'.php' );
        if($this->confirm('Do you wish to generate migrations for this model ? (yes|no)[no]'))
        {
            $this->makeMigration($class);
        }
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

    private function makeMigration($class)
    {
        $fields = $this->ask("Enter you comma separated fields:type Ex : name:string ");
        $fileName = Helpers\Migration::getFilename($class);
        $tableName = Helpers\Migration::getName($class);
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}','{{fields}}','{{tableName}}'],
                [$class,Str::lower($class),
                    Str::lower(Str::plural($class)),
                    SchemaParser::parseFields($fields),
                    $tableName
                ],
                Helpers\Stub::get('Migration')
            );
        file_put_contents(database_path("/migrations/".$fileName), $modelTemplate);
        $this->line('Migration File Created  : '.$fileName );

    }
}
