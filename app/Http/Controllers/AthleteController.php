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
            ->get();
    }


    public function addOrUpdate(string $athlete_id, $username, $firstname, $lastname, $city, $state, $country, $sex)
    {
        DB::table('athlete')
            ->updateOrInsert(
                ['athlete_id' => $athlete_id],
                ['username' => $username, 'firstname' => $firstname, 'lastname' => $lastname, 'city' => $city, 'state' => $state, 'country' => $country, 'sex' => $sex]
            );
    }
}
