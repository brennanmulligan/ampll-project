<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JsonParser;
use App\Objects\Athlete;
use http\Header\Parser;
use Illuminate\Http\RedirectResponse;

class GatewayController extends Controller
{
    /**
     * Function to register a user and add their information to the database
     * @return RedirectResponse
     */
    function login(): RedirectResponse
    {
        $apiController = new StravaAPIController();
        $parser = new JsonParser();
        $authData = $parser->parseAuthorizationData($apiController->getLoginData());

        $athlete = $authData->getAthlete();

        //put the athlete into the DB
        $athleteController = new AthleteController();
        $athleteController->addOrUpdate($athlete);

        //use the athlete id to store the tokens with their respective athlete in the Auth table
        $authController = new AuthController();
        $authController->storeTokens($authData);

        $this->storeActivitiesData($athlete->getId());
        return redirect('ui');
    }

    /**
     *
     * @return void
     */
    function verifyAuth() {

    }

    /**
     * Gets and Stores the data of an athlete based on a given ID
     * @param string $athlete_id Athlete information to be retrieved
     * @return void
     */
    function storeAthleteData(string $athlete_id) {
        $stravaAPIController = new StravaAPIController();
        $athleteData = $stravaAPIController->getAthleteData($athlete_id);

        //if the access token fails, it may have expired, and we need a new access token
        if(str_contains(serialize($athleteData), "Authorization Error")) {
            $decodedResult = $stravaAPIController->refreshAccessToken($athlete_id);

            //if the refresh token fails, reauthenticate by logging in
            if(str_contains(serialize($decodedResult), "inside_grant")) {
                $gatewayController = new GatewayController();
                $gatewayController->login();
            } else {
                $jsonParser = new JsonParser();
                $authController = new AuthController();

                $authData = $jsonParser->parseAuthorizationData($decodedResult);
                $authController->storeTokens($authData);
            }

            $athleteData = $stravaAPIController->getAthleteData($athlete_id);
        }

        $parser = new JsonParser();
        $athlete = $parser->parseAthleteData($athleteData);

        $athleteController = new AthleteController();
        $athleteController->addOrUpdate($athlete);
    }

    /**
     * Gets and Stores the data of the last 6 months of activities for an athlete
     * @param string $athlete_id Athlete information to be retrieved
     * @return void
     */
    function storeActivitiesData($athlete_id) {
        $stravaAPIController = new StravaAPIController();
        $activitiesData = $stravaAPIController->getActivitiesData($athlete_id);

        //if the access token fails, it may have expired, and we need a new access token
        if(str_contains(serialize($activitiesData), "Authorization Error")) {
            $stravaAPIController->refreshAccessToken($athlete_id);
            $activitiesData = $stravaAPIController->getActivitiesData($athlete_id);
        }

        $parser = new JsonParser();
        $activities = $parser->parseActivitiesData($activitiesData);

        $activitiesController = new ActivityController();
        $activitiesController->storeActivities($athlete_id, $activities);
    }
}