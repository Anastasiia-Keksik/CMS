<?php

namespace App\Repository;

use App\Entity\MainMenuCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainMenuCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainMenuCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainMenuCategory[]    findAll()
 * @method MainMenuCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainMenuCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainMenuCategory::class);
    }

    // /**
    //  * @return MainMenuCategory[] Returns an array of MainMenuCategory objects
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
    public function findOneBySomeField($value): ?MainMenuCategory
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
