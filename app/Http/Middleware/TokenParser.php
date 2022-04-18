<?php


namespace App\Http\Middleware;

use App\Models\Athlete;
use App\Objects\AuthorizationData;

class TokenParser
{
    /**
     * A function to encrypt a user's access and refresh token based on a given key
     *
     * @param string $decrypted_access_token - access token to encrypt
     * @param string $decrypted_refresh_token - refresh token to encrypt
     * @return AuthorizationData - Encrypted tokens with encryption iv
     */
    function encryptToken(string $decrypted_access_token, string $decrypted_refresh_token): AuthorizationData
    {
        $cipher = config('AppConstants.encryption_cipher');
        $iv_length = openssl_cipher_iv_length($cipher);
        $encryption_iv = openssl_random_pseudo_bytes($iv_length);

        return new AuthorizationData(
            openssl_encrypt($decrypted_access_token, $cipher, config('AppConstants.encryption_key'), 0,
                $encryption_iv),
            openssl_encrypt($decrypted_refresh_token, $cipher, config('AppConstants.encryption_key'), 0,
                $encryption_iv),
            new \App\Objects\Athlete(null, null),
            $encryption_iv
        );
    }

    /**
     * Function to decrypt a user's access or refresh token
     *
     * @param string $encrypted_token - Token to decrypt
     * @param string $encryption_iv - encryption iv
     * @return string - Decrypted token
     */
    function decrypt(string $encrypted_token, string $encryption_iv): string
    {
        return openssl_decrypt($encrypted_token, config('AppConstants.encryption_cipher'),
            config('AppConstants.encryption_key'), 0, $encryption_iv);
    }
}
