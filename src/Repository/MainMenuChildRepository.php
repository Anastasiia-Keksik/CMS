<?php

namespace App\Repository;

use App\Entity\MainMenuChild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainMenuChild|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainMenuChild|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainMenuChild[]    findAll()
 * @method MainMenuChild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainMenuChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $ek = parent::__construct($registry, MainMenuChild::class);
    }



    // /**
    //  * @return MainMenuChild[] Returns an array of MainMenuChild objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MainMenuChild
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
