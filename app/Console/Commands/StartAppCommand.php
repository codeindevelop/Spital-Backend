<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start app with check Kafka and other systems ...';

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
        $this->info("Prepare to start configs and requirement systems ... ");

        $this->newLine();

        // Database fresh migration
        $this->call('migrate');
        $this->call('view:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('config:clear');


        $this->newLine();




        // Return commands success message ...
        $this->info("Aqupila System has ben Setup");
    }
}
