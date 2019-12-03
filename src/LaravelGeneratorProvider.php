<?php

namespace Chiheb\Generator;

use Illuminate\Support\ServiceProvider;
use Chiheb\Generator\Commands\GenerateCommand;

class LaravelGeneratorProvider extends ServiceProvider
{



    public function boot()
    {
        # code...
        $this->commands([
            GenerateCommand::class,
        ]);
    }

    public function register()
    {
        # code...
    }
}
