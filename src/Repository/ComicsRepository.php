<?php

namespace App\Repository;

use App\Entity\Comics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comics[]    findAll()
 * @method Comics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comics::class);
    }

    // /**
    //  * @return Comics[] Returns an array of Comics objects
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
    public function findOneBySomeField($value): ?Comics
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
