<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialized All requirement Auth configs';

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
     * @return void
     */
    public function handle(): void
    {
        // Database fresh migration with seed data
        $this->call('migrate:fresh', ['--seed' => 'default']);


        $this->newLine();

        // Install Laravel Passport Configuration
        $this->call('passport:install');


        // Return commands success message ...
        $this->info("Aqupila All Services Is Ready");
    }
}
