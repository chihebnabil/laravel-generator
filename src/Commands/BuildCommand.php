<?php

namespace Chiheb\Generator\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\VarDumper\Cloner\Stub;
use Symfony\Component\Yaml\Yaml;
use Chiheb\Generator\Helpers;
use Chiheb\Generator\Helpers\SchemaParser;

class BuildCommand extends Command
{
    protected $signature = 'generator:build {schema=schema.yaml}';


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
        $schema = $this->argument('schema');
        $path = base_path('./'.$schema);
        $modules = Yaml::parseFile($path);



        foreach ($modules as $k => $module){
            $this->info("Generating ".$k." module structure");
            Artisan::call("module:make ". $k);

            if(array_key_exists("Models",$module)){
                foreach ($module['Models'] as $m => $model){
                    $this->info("Generating ".$m." Entity");
                    $fields = "";
                    if(array_key_exists("fields",$model)){
                        foreach ($model['fields'] as $f => $t){
                            $fields .= $f .',';
                        }
                        $fields = rtrim($fields,",");
                    }


                    $this->call("generator:make",[
                        'type' => "Model",
                        'class' => $m,
                        'fields' => $fields
                    ]);
                }

            }


            if(array_key_exists("Controllers",$module)){
                foreach ($module['Controllers'] as  $controller){
                    $this->info("Generating ".$m." Controller");
                    $this->call("generator:make",[
                        'type' => $controller['type'],
                        'class' => $controller['name'],
                    ]);
                }

            }



        }








    }
}