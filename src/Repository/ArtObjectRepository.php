<?php

namespace App\Repository;

use App\Entity\ArtObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtObject[]    findAll()
 * @method ArtObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtObject::class);
    }

    // /**
    //  * @return ArtObject[] Returns an array of ArtObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArtObject
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
