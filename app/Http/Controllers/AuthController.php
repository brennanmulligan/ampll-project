<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TokenParser;
use App\Models\Auth;
use App\Objects\AuthorizationData;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param $athlete_id
     * @return Auth
     */
    public function getAuthTokens($athlete_id): Auth
    {
        // we know that there will only be one since athlete_id is our primary key so we use first
        $auth = Auth::where("athlete_id", "=", $athlete_id)
            ->first();

        $tp = new TokenParser();

        // Decrypt the tokens for use
        $auth->access_token = $tp->decrypt($auth->access_token);
        $auth->refresh_token = $tp->decrypt($auth->refresh_token);

        return $auth;
    }

    public function getValid($athlete_id) {
        $valid = Auth::where('athlete_id', '=', $athlete_id)->first()->valid;
        if($valid == 1) {
            return True;
        } else {
            return False;
        }
    }

    /**
     * Stores tokens into the DB based on an athlete id
     * @param $authData AuthorizationData
     * @return void
     */
    public function storeTokens($athleteID, $accessToken, $refreshToken) {
        $tp = new TokenParser();
        Auth::updateOrCreate(
                ['athlete_id' => $athleteID],
                ['access_token' => $tp->encryptToken($accessToken), 'refresh_token' => $tp->encryptToken($refreshToken), 'valid' => 1]
            );
    }

    public function setInvalid($athleteID) {
        Auth::where('athlete_id', $athleteID)->update(['valid' => -1]);
    }
}
