<?php

namespace App\Repository;

use App\Entity\ComicCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComicCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComicCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComicCategories[]    findAll()
 * @method ComicCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComicCategories::class);
    }

    // /**
    //  * @return ComicCategories[] Returns an array of ComicCategories objects
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
    public function findOneBySomeField($value): ?ComicCategories
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
