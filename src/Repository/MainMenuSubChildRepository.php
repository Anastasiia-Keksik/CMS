<?php

namespace App\Repository;

use App\Entity\MainMenuSubChild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainMenuSubChild|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainMenuSubChild|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainMenuSubChild[]    findAll()
 * @method MainMenuSubChild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainMenuSubChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainMenuSubChild::class);
    }

    // /**
    //  * @return MainMenuSubChild[] Returns an array of MainMenuSubChild objects
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
    public function findOneBySomeField($value): ?MainMenuSubChild
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
