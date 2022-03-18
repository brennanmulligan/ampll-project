<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\Auth;
use App\Objects\Athlete;
use App\Objects\AuthorizationData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

//manually tested and verified database storage. tests are broken and will be fixed later to be automated

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuthEntryExists()
    {
        $auth_controller = new AuthController();

        $auth_data = $auth_controller->getAuthTokens('69703678988888');

        self::assertNotEmpty($auth_data);

        self::assertEquals('69703678988888', $auth_data->athlete_id);
    }

    public function testVerifyStorage() {
        $authController = new AuthController();
        $authData = new AuthorizationData('69703678988888', Str::random(40), new Athlete());
        $authController->storeTokens($authData);
        $auth = $authController->getAuthTokens('69703678988888');
        self::assertEquals('69703678988888', $auth->athlete_id);
        self::assertNotNull($auth->access_token);
        self::assertNotNull($auth->refresh_token);
    }
}
