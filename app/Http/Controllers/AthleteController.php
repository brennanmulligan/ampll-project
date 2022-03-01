<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AthleteController extends Controller
{
    public function getAthlete(string $athlete_id)
    {
        return DB::table('athlete')
            ->select("*")
            ->where('athlete_id', '=', $athlete_id)
            ->first();
    }


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
