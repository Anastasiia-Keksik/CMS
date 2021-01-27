<?php

namespace App\Repository;

use App\Entity\Bans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bans[]    findAll()
 * @method Bans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bans::class);
    }

    public function getBan($user)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.User = :val')
            ->setParameter('val', $user)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Bans
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
