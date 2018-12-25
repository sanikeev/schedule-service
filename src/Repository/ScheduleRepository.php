<?php

namespace App\Repository;

use App\Entity\Courier;
use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    public function isBusyCourier(Courier $courier, \DateTimeInterface $date)
    {
        $qb = $this->createQueryBuilder('e');
        return $qb->where('e.courier = :courier')
            ->andWhere('e.startedAt <= :date')
            ->andWhere('e.endedAt >= :date')
            ->setParameters([
                'courier' => $courier,
                'date' => $date,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
