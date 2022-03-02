<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JsonParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StravaAPIController extends Controller
{
    function getLoginData() {
        $code = $_GET['code'];
        $loginData = Http::post('https://www.strava.com/api/v3/oauth/token', [
            'client_id' => config('AppConstants.client_id'),
            'client_secret' => config('AppConstants.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        return json_decode($loginData, false);
    }

    /**
     * Makes an API call to Strava that will get the data for a user based on an access token
     * @param $athlete_id String An athlete's unique ID that we can use to get their access token from the DB
     * @return mixed A decoded JSON object that holds a user's information
     */
    function getAthleteData(string $athlete_id): mixed {
        $authController = new AuthController();
        $accessToken = ($authController->getAuthTokens($athlete_id))->access_token;
        $accessToken = 'Bearer ' . $accessToken;
        $userData = Http::withHeaders(['Authorization' => $accessToken])->
            get('https://www.strava.com/api/v3/athlete');
        return json_decode($userData, false);
    }

    /**
     * Makes an API  to Strava that will get the activities for an athlete from the last 6 months based on
     * the athlete's access token
     * @param $athleteID String An athlete's unique ID that we can use to get their access token from the DB
     * @return mixed A decoded JSON object that holds the data for all of an athlete's activities
     */
    function getActivitiesData(string $athleteID): mixed {
        //this is in epoch time (time since the epoch in seconds)
        $currentTime = time();
        //very close estimate to how long 6 months is in seconds
        $oldTime = $currentTime - 15770000;
        $authController = new AuthController();
        $accessToken = ($authController->getAuthTokens($athleteID))->access_token;
        $accessToken = 'Bearer ' . $accessToken;
        /* have old time commented out to see all activities since none of us have any
         * activities in recent months for testing purposes
         */
        //180 as the per_page because we are doing 6 months of data. assume that there is max of 1 activity/day for now
        $activityData = Http::withHeaders(['Authorization' => $accessToken])->
        get('https://www.strava.com/api/v3/athlete/activities?before=' . $currentTime . '&after=' . 0/*$oldTime*/ .
        '&per_page=' . 180);
        return json_decode($activityData, false);
    }

    /**
     * Takes in an athlete ID and uses its stored refresh token to retrieve a new access token and return it.
     * This is intended to be used when an access token causes an Authorization Error.
     * @param string $athlete_ID
     * @return mixed The decoded access token
     */
    function refreshAccessToken(string $athlete_ID): mixed {
        //retrieves current auth tokens (We want the new refresh token)
        $authController = new AuthController();
        $auth = $authController->getAuthTokens($athlete_ID);

        //parameters for curl post request
        $fields = [
            'client_id' => config('AppConstants.client_id'),
            'client_secret' => config('AppConstants.client_secret'),
            'refresh_token' => $auth->refresh_token,
            'grant_type' => 'refresh_token',
        ];

        $result = $this->curlRefreshToken($fields);
        return json_decode($result, false);
    }

    function curlRefreshToken($fields): string|bool {
        //create curl post request for new access token
        $postData = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.strava.com/api/v3/oauth/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //get result of curl request
        return curl_exec($ch);
    }
}