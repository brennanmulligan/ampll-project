<?php

namespace App\Objects;

class Athlete
{
    private $id;
    private $username;
    private $firstName;
    private $lastName;
    private $city;
    private $state;
    private $country;
    private $sex;
    private $updated_at;

    function __construct($id, $username, $firstName, $lastName, $city, $state, $country, $sex, $updated_at)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->sex = $sex;
        $this->updated_at = $updated_at;
    }

    //Setters not needed because we won't be pushing data to strava
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}