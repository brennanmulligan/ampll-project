<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all athletes which need their activity data refreshed and update it.';

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
     * @return int
     */
    public function handle()
    {
        // Call database and get all athlete ids which need updated

        // foreach through ids
            // call strava api and get list of new activities within last 6 months
                // if refresh data isn't valid set field in database and break
                // loop through activities and store or update them (there should be a way to do this in a single call)
            // update athlete's refresh_at
    }
}
