<?php

namespace App\Repository;

use App\Entity\Themese;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Themese|null find($id, $lockMode = null, $lockVersion = null)
 * @method Themese|null findOneBy(array $criteria, array $orderBy = null)
 * @method Themese[]    findAll()
 * @method Themese[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Themese::class);
    }

    // /**
    //  * @return Themese[] Returns an array of Themese objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Themese
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
