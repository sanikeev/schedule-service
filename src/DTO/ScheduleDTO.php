<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.12.18
 * Time: 17:26
 */

namespace App\DTO;


class ScheduleDTO
{

    protected $courier;
    protected $city;
    protected $startedAt;

    /**
     * @return mixed
     */
    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * @param mixed $courier
     */
    public function setCourier($courier): void
    {
        $this->courier = $courier;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param mixed $startedAt
     */
    public function setStartedAt($startedAt): void
    {
        $this->startedAt = $startedAt;
    }


}