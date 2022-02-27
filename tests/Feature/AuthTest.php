<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

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

        self::assertEquals('69703678988888', $auth_data[0]->athlete_id);
    }

    public function testVerifyStorage() {
        $auth_controller = new AuthController();
        $auth_controller->storeTokens('69703678988888', Str::random(40), Str::random(40));
        $auth_data = $auth_controller->getAuthTokens('69703678988888');
        self::assertEquals('69703678988888', $auth_data[0]->athlete_id);
        self::assertNotNull($auth_data[0]->access_token);
        self::assertNotNull($auth_data[0]->refresh_token);
    }
}
