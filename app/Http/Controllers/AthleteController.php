<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AthleteController extends Controller
{
    /**
     * Get's an athlete's DB entry based on their ID
     * @param string $athlete_id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getAthlete(string $athlete_id)
    {
        return DB::table('athlete')
            ->select("*")
            ->where('athlete_id', '=', $athlete_id)
            ->first();
    }

    /**
     * Pulls all athletes from the DB
     * @return \Illuminate\Support\Collection athletes from database
     */
    public function getAllAthletes()
    {
        return DB::table('athlete')
            ->select("*")
            ->get();
    }

    /**
     * takes an athlete object and uses all of it's pieces to store it into the DB
     * @param $athlete
     * @return void
     */
    public function addOrUpdate($athlete)
    {
        Athlete::updateOrCreate(
                ['athlete_id' => $athlete->getId()],
                ['username' => $athlete->getUsername(), 'first_name' => $athlete->getFirstName(),
                    'last_name' => $athlete->getLastname(), 'city' => $athlete->getCity(),
                    'state' => $athlete->getState(), 'country' => $athlete->getCountry(), 'sex' => $athlete->getSex()]
            );
    }

    /**
     * @return \Illuminate\Support\Collection
     * function to return athletes past their next_sync_time
     */
    public function getAthletesToRefresh() {
        return DB::table('athlete')
            ->select("*")
            ->where('next_sync_time', '<', date("Y-m-d H:i:s", time()))
            ->get();
    }

    /**
     * @param mixed $athleteID
     * @param int $seconds
     * @return void
     * Update an athlete's next_sync_time in the standard format
     */
    public function updateNextSyncTime(mixed $athleteID, int $seconds) {
        $new_sync_time = date("Y-m-d H:i:s", time() + $seconds);

        DB::table('athlete')
            ->select("*")
            ->where('athlete_id', '=', $athleteID)
            ->update(['next_sync_time' => $new_sync_time]);
    }
}
