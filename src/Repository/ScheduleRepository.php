<?php

namespace App\Repository;

use App\Entity\Courier;
use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
            ->andWhere('e.startedAt <= :started_at')
            ->andWhere('e.endedAt >= :started_at')
            ->setParameters([
                'courier' => $courier,
                'started_at' => $date,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getByPeriod(\DateTimeInterface $start, \DateTimeInterface $end, $page = 0)
    {
        $maxResults = 30;
        $offset = $maxResults * $page;
        $query = $this->createQueryBuilder('e')
            ->join('App\Entity\City', 'c')
            ->join('App\Entity\Courier', 'u')
            ->where('e.startedAt >= :start')
            ->andWhere('e.endedAt <= :endDate')
            ->setParameter('start', $start->format('Y-m-d'))
            ->setParameter('endDate', $end->format('Y-m-d'))
            ->orderBy('e.endedAt', 'desc')
            ->setMaxResults($maxResults)
            ->setFirstResult($offset)
            ->getQuery()

        ;

        $paginator = new Paginator($query, true);

        $data = $paginator->getIterator();

        return [
            'total' => $paginator->count(),
            'totalPages' => (int) ceil($paginator->count() / $maxResults),
            'data' => $data,
        ];
    }
}
