<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JsonParser;

class GatewayController extends Controller
{
    function login()
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
    }

    function displayUserData() {

    }
}