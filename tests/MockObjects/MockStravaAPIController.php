<?php

namespace tests\MockObjects;

use App\Http\Controllers\StravaAPIController;

class MockStravaAPIController extends StravaAPIController
{
    function getLoginData() {

    }

    function getAthleteData(string $athlete_id): mixed {

    }

    function getActivitiesData(string $athleteID): mixed {

    }

    function refreshAccessToken(string $athlete_ID): mixed {

    }

    function curlRefreshToken($fields): string|bool {

    }
}