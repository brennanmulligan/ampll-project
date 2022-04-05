<?php

namespace App\Console\Commands;

use App\Http\Controllers\AthleteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\StravaAPIController;
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
     * @return void
     */
    public function handle()
    {
        $athleteController = new AthleteController();
        $athletes = $athleteController->getAthletesToRefresh();
        $authController = new AuthController();
        // Call database and get all athlete ids which need updated

        $gatewayController = new GatewayController();
        foreach ($athletes as $athlete) {
            if($authController->getValid($athlete->athlete_id)) {
                $gatewayController->storeActivitiesData($athlete->athlete_id);
            }
        }
        $this->info('Successfully got new activities.');
    }
}
