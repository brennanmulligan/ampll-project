<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JsonParser;
use App\Models\Auth;
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
        $authController->storeTokens($authData->getAthlete()->getId(), $authData->getAccessToken(), $authData->getRefreshToken());

        $this->storeActivitiesData($athlete->getId());
        return redirect('ui');
    }

    /**
     * Gets and Stores the data of an athlete based on a given ID
     * @param string $athleteID Athlete information to be retrieved
     * @return void|int
     */
    function storeAthleteData(string $athleteID) {
        $stravaAPIController = new StravaAPIController();
        $athleteData = $stravaAPIController->getAthleteData($athleteID);

        //if the access token fails, it may have expired, and we need a new access token
        if(str_contains(serialize($athleteData), "Authorization Error")) {
            $decodedResult = $stravaAPIController->refreshAccessToken($athleteID);

            //if the refresh token fails, reauthenticate by logging in
            // if it is -1 it was invalid in the database
            if($decodedResult == -1 || str_contains(serialize($decodedResult), "Bad Request")) {
                return -1;
            } else {
                $jsonParser = new JsonParser();
                $authController = new AuthController();

                $refreshData = $jsonParser->parseRefreshData($decodedResult);
                $authController->storeTokens($athleteID, $refreshData->getAccessToken(), $refreshData->getRefreshToken());
            }

            $athleteData = $stravaAPIController->getAthleteData($athleteID);
        }

        $parser = new JsonParser();
        $athlete = $parser->parseAthleteData($athleteData);

        $athleteController = new AthleteController();
        $athleteController->addOrUpdate($athlete);
    }

    /**
     * Gets and Stores the data of the last 6 months of activities for an athlete
     * @param string $athleteID Athlete information to be retrieved
     * @return int|void
     */
    function storeActivitiesData($athleteID) {
        $stravaAPIController = new StravaAPIController();
        $activitiesData = $stravaAPIController->getActivitiesData($athleteID);

        //if the access token fails, it may have expired, and we need a new access token
        if(str_contains(serialize($activitiesData), "Authorization Error")) {
            $decodedResult = $stravaAPIController->refreshAccessToken($athleteID);

            //if the refresh token fails, reauthenticate by logging in
            // if it is -1 it was invalid in the database
            if($decodedResult == -1 || str_contains(serialize($decodedResult), "Bad Request")) {
                return -1;
            } else {
                $jsonParser = new JsonParser();
                $authController = new AuthController();

                $refreshData = $jsonParser->parseRefreshData($decodedResult);
                $authController->storeTokens($athleteID, $refreshData->getAccessToken(), $refreshData->getRefreshToken());
            }

            $activitiesData = $stravaAPIController->getActivitiesData($athleteID);
        }

        $parser = new JsonParser();
        $activities = $parser->parseActivitiesData($activitiesData);

        $activitiesController = new ActivityController();
        $activitiesController->storeActivities($athleteID, $activities);
    }

    /**
     * Refreshes the data in the database for both Athlete and activities
     * @return int|void A -1 return value is an unauthorized athlete
     */
    function refreshData($athleteID)
    {
        if ($this->storeAthleteData($athleteID) == -1) {
            $authController = new AuthController();
            $authController->setInvalid($athleteID);
            return -1;
        }
        if ($this->storeActivitiesData($athleteID) == -1) {
            $authController = new AuthController();
            $authController->setInvalid($athleteID);
            return -1;
        }
    }
}