<?php

namespace App\Http\Controllers;

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
    function getUserData(string $athlete_id): mixed {
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
     * @param $athlete_id String An athlete's unique ID that we can use to get their access token from the DB
     * @return mixed A decoded JSON object that holds the data for all of an athlete's activities
     */
    function getActivitiesData(string $athlete_id): mixed {
        //this is in epoch time (time since the epoch in seconds)
        $currentTime = time();
        //very close estimate to how long 6 months is in seconds
        $oldTime = $currentTime - 15770000;
        $authController = new AuthController();
        $accessToken = ($authController->getAuthTokens($athlete_id))->access_token;
        $accessToken = 'Bearer ' . $accessToken;
        /* have old time commented out to see all activities since none of us have any
         * activities in recent months for testing purposes
         */
        $activityData = Http::withHeaders(['Authorization' => $accessToken])->
        get('https://www.strava.com/api/v3/athlete/activities?before=' . $currentTime . '&after=' . 0);//$oldTime);
        return json_decode($activityData, false);
    }
}
