<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.12.18
 * Time: 20:09
 */

namespace App\Service;


use App\Entity\City;

class ScheduleService
{

    public function calcArrivalDate(City $city, \DateTimeInterface $date): \DateTimeInterface
    {
        $day = ceil($city->getTripDuration() / 2);
        return $date->modify(sprintf('+%d day', $day));
    }

    public function calcFinishDate($duration, \DateTimeInterface $date): \DateTimeInterface
    {
        return $date->modify(sprintf('+%d day', $duration));
    }
}