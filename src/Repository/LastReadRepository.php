<?php

namespace App\Repository;

use App\Entity\LastRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LastRead|null find($id, $lockMode = null, $lockVersion = null)
 * @method LastRead|null findOneBy(array $criteria, array $orderBy = null)
 * @method LastRead[]    findAll()
 * @method LastRead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LastReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LastRead::class);
    }

    // /**
    //  * @return LastRead[] Returns an array of LastRead objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LastRead
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
