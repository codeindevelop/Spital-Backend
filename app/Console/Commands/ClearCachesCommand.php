<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCachesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear All Caches';

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
        $this->call('cache:clear');
        $this->info("All Caches has ben Clear");

        $this->newLine();

        $this->call('config:clear');
        $this->info("All Config has ben Clear");

        $this->newLine();

        $this->call('route:clear');


        // Return commands success message ...
        $this->info("Spital Clear All Caches And Services Is Ready");
    }
}
