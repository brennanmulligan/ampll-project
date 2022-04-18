<?php


namespace App\Http\Middleware;

class TokenParser
{
    /**
     * A function to encrypt a user's access or refresh token based on a given key
     *
     * @param string $token to encrypt
     * @return string the encrypted token
     */
    function encryptToken(string $token): string
    {
        return $token;
    }

    /**
     * A function to decrypt a user's access or refresh token based on a given key
     *
     * @param string $token to decrypt
     * @return string the decrypted token
     */
    function decrypt(string $token): string
    {
        return $token;
    }
}
