<?php

namespace App\Repository;

use App\Entity\MainMenuSubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainMenuSubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainMenuSubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainMenuSubCategory[]    findAll()
 * @method MainMenuSubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainMenuSubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainMenuSubCategory::class);
    }

    // /**
    //  * @return MainMenuSubItem[] Returns an array of MainMenuSubItem objects
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
    public function findOneBySomeField($value): ?MainMenuSubItem
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
