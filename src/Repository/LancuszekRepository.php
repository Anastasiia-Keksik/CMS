<?php

namespace App\Repository;

use App\Entity\Lancuszek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lancuszek|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lancuszek|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lancuszek[]    findAll()
 * @method Lancuszek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LancuszekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lancuszek::class);
    }

    // /**
    //  * @return Lancuszek[] Returns an array of Lancuszek objects
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
    public function findOneBySomeField($value): ?Lancuszek
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
