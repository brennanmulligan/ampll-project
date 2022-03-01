<?php

namespace App\Http\Middleware;

use App\Objects\Athlete;
use App\Objects\AuthorizationData;

Class JsonParser {

    function parseAuthorizationData($authorizationData) {
        $athlete = $this->parseAthleteData($authorizationData->athlete);
        return new AuthorizationData($authorizationData->access_token, $authorizationData->refresh_token, $athlete);
    }

    /**
     * A function to get the information from specfic fields in a decoded JSON object
     * @param $userData mixed A decoded JSON object that we will parse in order to get the data we need to store
     * @return Athlete
     */
    function parseAthleteData(mixed $userData) {
        return new Athlete($userData->id, $userData->username, $userData->firstname, $userData->lastname,
            $userData->city, $userData->state, $userData->country, $userData->sex);
    }

    /**
     * A function that will get all of the activities from an athlete by parsing a JSON object
     * @param $activitiesData mixed A decoded JSON object that holds the data for all of an athlete's activities
     * in the last 6 months
     * @return void
     */
    function parseActivitiesData(mixed $activitiesData) {

    }
}
