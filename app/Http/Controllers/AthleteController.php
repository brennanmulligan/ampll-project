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
     * @param $time
     * @return \Illuminate\Support\Collection
     * function that can get all athletes that haven't been refreshed within a certain amount of time
     */
    public function getAthletesBeforeTime($time) {
        //since refreshed_at is in epoch time, we can just see if it's less than (same as before) the time we send it
        return DB::table('athlete')
            ->select("*")
            ->where('refreshed_at', '<', $time)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     * function to return athletes past their next_sync_time
     */
    public function getAthletesToRefresh() {
        //since refreshed_at is in epoch time, we can just see if it's less than (same as before) the time we send it
        return DB::table('athlete')
            ->select("*")
            ->where('next_sync_time', '<', time())
            ->get();
    }

    /**
     * @param mixed $athleteID
     * @param int $seconds
     * @return void
     * function to update an athlete's next_sync_time
     */
    public function updateSyncTime(mixed $athleteID, int $seconds) {
        DB::table('athlete')
            ->updateOrInsert(
                ['athlete_id' => $athleteID],
                ['next_sync_time' => time() + $seconds] //This is number of seconds in a week
            );
    }
}
