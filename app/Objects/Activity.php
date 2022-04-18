<?php

namespace App\Objects;

class Activity
{
    private $id;
    private $name;
    private $type;
    private $elapsedTime;
    private $distance;
    private $totalElevationGain;
    private $startDate;
    private $startDateLocal;
    private $UTCOffset;
    private $kudosCount;
    private $private;

    function __construct($id, $name, $type, $elapsedTime = null, $distance = null, $totalElevationGain = null,
                         $startDate = null, $startDateLocal = null, $UTCOffset = null, $kudosCount = null, $private = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->elapsedTime = $elapsedTime;
        $this->distance = $distance;
        $this->totalElevationGain = $totalElevationGain;
        $this->startDate = $startDate;
        $this->startDateLocal = $startDateLocal;
        $this->UTCOffset = $UTCOffset;
        $this->kudosCount = $kudosCount;
        $this->private = $private;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @return mixed
     */
    public function getTotalElevationGain()
    {
        return $this->totalElevationGain;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getStartDateLocal()
    {
        return $this->startDateLocal;
    }

    /**
     * @return mixed
     */
    public function getUTCOffset()
    {
        return $this->UTCOffset;
    }

    /**
     * @return mixed
     */
    public function getKudosCount()
    {
        return $this->kudosCount;
    }

    public function getPrivate()
    {
        return $this->private;
    }
}