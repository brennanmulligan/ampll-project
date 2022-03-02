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
     * @return All athletes from database
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
        DB::table('athlete')
            ->updateOrInsert(
                ['athlete_id' => $athlete->getId()],
                ['username' => $athlete->getUsername(), 'first_name' => $athlete->getFirstName(),
                    'last_name' => $athlete->getLastname(), 'city' => $athlete->getCity(),
                    'state' => $athlete->getState(), 'country' => $athlete->getCountry(), 'sex' => $athlete->getSex()]
            );
    }
}
