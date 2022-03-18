<?php

namespace tests\MockObjects;

use App\Http\Controllers\AuthController;

class MockAuthController extends AuthController
{
    public function getAuthTokens($athlete_id)
    {

    }

    public function storeTokens($athleteID, $accessToken, $refreshToken) {

    }
}