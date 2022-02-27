<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getAuthTokens($athlete_id) {
        return Auth::where("athlete_id", "=", $athlete_id)
            ->get();
    }
    //stores tokens into the DB based on an athlete id
    public function storeTokens($athlete_id, $access_token, $refresh_token) {
        Auth::updateOrInsert(
                ['athlete_id' => $athlete_id],
                ['access_token' => $access_token, 'refresh_token' => $refresh_token]
            );
    }
}
