<?php

namespace Chiheb\Generator\Commands;

use Chiheb\Generator\Helpers\SchemaParser;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\VarDumper\Cloner\Stub;

use Chiheb\Generator\Helpers;

class BuildCommand extends Command
{
    protected $signature = 'generator:make';


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
}