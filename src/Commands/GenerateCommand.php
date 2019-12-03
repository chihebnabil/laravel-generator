<?php

namespace Chiheb\Generator\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\VarDumper\Cloner\Stub;


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
        $type  = $this->choice('What do you want to  generate?', ['Model', 'Controller','DataTableController','Crud'], null);
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
                break;
            case 'Crud':
                $this->makeModel($class);
                $this->makeDataTableController($class);
                $this->makeTableView($class);
                break;
        }
    }


    protected function getStub($type)
    {
        switch ($type){
            case 'Model':
                return file_get_contents(__DIR__ . '/../stubs/model.stub');
                break;
            case 'Controller':
                return file_get_contents(__DIR__ . '/../stubs/controller.stub');
            case 'DataTableController':
                return file_get_contents(__DIR__ . '/../stubs/controller_datatable.stub');
                break;
            case 'ViewTable':
                return file_get_contents(__DIR__ .'/../stubs/vue/table.stub');
                break;
        }
    }
    private function makeModel($class)
    {
        $fields = $this->ask("Enter you comma separated fields Ex : name , category_id ");
        $modelTemplate =
            str_replace(
                ['{{class}}','{{namespace}}','{{fields}}','{{table}}'],
                [$class,"App\\".$class,$fields,strtolower(Str::plural($class))],
                $this->getStub('Model')
            );
        file_put_contents(app_path("/{$class}.php"), $modelTemplate);
    }

    private function makeController($class)
    {
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                $this->getStub('Controller')
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
                $this->getStub('DataTableController')
            );
        file_put_contents(app_path("/Http/Controllers/{$class}Controller.php"), $modelTemplate);
    }
    private function makeTableView($class)
    {
        $modelTemplate =
            str_replace(
                ['{{class}}','{{lowerCaseModel}}','{{lowerCasePlural}}'],
                [$class,Str::lower($class),Str::lower(Str::plural($class))],
                $this->getStub('ViewTable')
            );
        file_put_contents(app_path("/{$class}Table.vue"), $modelTemplate);
    }
}
