<?php

namespace Daemswibowo\Camvret\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'camvret:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Camvret Laravel Starter Kit';

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
        $this->warn('Installing resources');
        Artisan::call('vendor:publish', ['--tag'=>'camvret','--force' => true]);
        $this->warn('Publishing config files');
        Artisan::call('vendor:publish', ['--tag'=>'config','--force' => true]);
        $this->info("Camvret resources successfully installed!");
        $this->info("Now run 'npm install'!");
    }
}
