<?php

namespace App\Repository;

use App\Entity\IpLastLogonLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IpLastLogonLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpLastLogonLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpLastLogonLog[]    findAll()
 * @method IpLastLogonLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpLastLogonLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpLastLogonLog::class);
    }

    // /**
    //  * @return IpLastLogonLog[] Returns an array of IpLastLogonLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IpLastLogonLog
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
