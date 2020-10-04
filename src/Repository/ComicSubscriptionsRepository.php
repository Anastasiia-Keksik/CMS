<?php

namespace App\Repository;

use App\Entity\ComicSubscriptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComicSubscriptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComicSubscriptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComicSubscriptions[]    findAll()
 * @method ComicSubscriptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicSubscriptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComicSubscriptions::class);
    }

    // /**
    //  * @return ComicSubscriptions[] Returns an array of ComicSubscriptions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComicSubscriptions
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
