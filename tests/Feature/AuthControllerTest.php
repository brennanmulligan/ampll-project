<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private AuthController $authController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed('AthleteTableSeeder');
        $this->seed('AuthTableSeeder');
        $this->authController = new AuthController();
    }

    /**
     * Tests that we can properly pull auth tokens from the DB
     *
     * @return void
     */
    public function testGetAuthTokens()
    {
        $tp = new TokenParser();
        $auth = $this->authController->getAuthTokens('123456789');
        $this->assertNotEmpty($auth);
    }

    /**
     * Tests that the valid function works properly
     * @return void
     */
    public function testGetValid() {
        $this->assertTrue($this->authController->getValid('123456789'));
        $this->assertFalse($this->authController->getValid('987654321'));
    }

    public function testStoreTokens() {
        $this->assertDatabaseMissing('auth', ['athlete_id' => '123123123']);
        $this->authController->storeTokens('123123123', '12ab3c45de', 'ab12c3de45');
        $this->assertDatabaseHas('auth', ['athlete_id' => '123123123']);
    }

    /**
     * Tests that we can properly change the valid field in the DB for an auth entry
     * @return void
     */
    public function testSetInvalid() {
        $this->assertTrue($this->authController->getValid('123456789'));
        $this->authController->setInvalid('123456789');
        $this->assertFalse($this->authController->getValid('123456789'));

    }
}
