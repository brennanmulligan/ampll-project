<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTests extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function authEntryExists()
    {
        $auth_controller = new AuthController();

        $auth_data = $auth_controller->getAuthTokens('69703678988888');

        self::assertNotEmpty($auth_data);

        self::assertEquals('69703678988888', $auth_data[0]->athlete_id);
    }
}
