<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.12.18
 * Time: 23:28
 */

namespace App\Criteria;


use App\Entity\Courier;
use Doctrine\Common\Collections\Criteria;

class BusyCourierCriteria
{

    public function __construct(Courier $courier, \DateTimeInterface $date)
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('courier', $courier))
            ->andWhere(Criteria::expr()->gte('startedAt', $date))
            ->andWhere(Criteria::expr()->lte('endedAt', $date))
            ;
    }
}