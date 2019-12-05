<?php

namespace Chiheb\Generator;

use Chiheb\Generator\Commands\BuildCommand;
use Illuminate\Support\ServiceProvider;
use Chiheb\Generator\Commands\GenerateCommand;
use Nwidart\Modules\ModulesServiceProvider;

class LaravelGeneratorProvider extends ServiceProvider
{



    public function boot()
    {
        # code...
        $this->commands([
            GenerateCommand::class,
            BuildCommand::class
        ]);
    }

    public function register()
    {

    }
}
