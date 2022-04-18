<?php

namespace App\Objects;

class AuthorizationData
{
    private $accessToken;
    private $refreshToken;
    private $athlete;
    private string $encryption_iv;

    function __construct($accessToken, $refreshToken, Athlete $athlete, string $encryption_iv = null)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->athlete = $athlete;
        if (!is_null($encryption_iv)) $this->encryption_iv = $encryption_iv;
    }

    /**
     * @return string
     */
    public function getEncryptionIv(): string
    {
        return $this->encryption_iv;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return mixed
     */
    public function getAthlete()
    {
        return $this->athlete;
    }
}