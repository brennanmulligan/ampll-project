<?php

namespace Tests\Feature;

use App\Http\Middleware\TokenParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncryptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that we properly store multiple activites into the DB
     *
     * @return void
     */
    public function testEncryptAndDecrypt()
    {
        $access_token = 'Scott is the best.';
        $refresh_token = 'Scott is still the best.';

        $tp = new TokenParser();

        $authData = $tp->encryptToken($access_token, $refresh_token);

        self::assertNotEquals($authData->getAccessToken(), $access_token);
        self::assertNotEquals($authData->getRefreshToken(), $refresh_token);

        $decrypted_access_token = $tp->decrypt($authData->getAccessToken(), $authData->getEncryptionIv());
        $decrypted_refresh_token = $tp->decrypt($authData->getRefreshToken(), $authData->getEncryptionIv());

        self::assertEquals($access_token, $decrypted_access_token);
        self::assertEquals($refresh_token, $decrypted_refresh_token);
    }
}
