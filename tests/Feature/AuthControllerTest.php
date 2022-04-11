<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
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
        $auth = $this->authController->getAuthTokens('123456789');
        $this->assertNotEmpty($auth);
        $this->assertEquals('1a2b3c4d5e', $auth->refresh_token);
        $this->assertEquals('a1b2c3d4e5', $auth->access_token);
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
        $this->assertDatabaseMissing('auth', ['access_token' => '12ab3c45de']);
        $this->assertDatabaseMissing('auth', ['refresh_token' => 'ab12c3de45']);
        $this->authController->storeTokens('123123123', '12ab3c45de', 'ab12c3de45');
        $this->assertDatabaseHas('auth', ['access_token' => '12ab3c45de']);
        $this->assertDatabaseHas('auth', ['refresh_token' => 'ab12c3de45']);
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
