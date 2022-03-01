<?php

namespace App\Objects;

class AuthorizationData
{
    private $accessToken;
    private $refreshToken;
    private $athlete;

    function __construct($accessToken, $refreshToken, Athlete $athlete)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->athlete = $athlete;
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