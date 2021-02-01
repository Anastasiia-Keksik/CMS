<?php

namespace App\Repository;

use App\Entity\ArtScene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtScene|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtScene|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtScene[]    findAll()
 * @method ArtScene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtSceneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtScene::class);
    }

    // /**
    //  * @return ArtScene[] Returns an array of ArtScene objects
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
    public function findOneBySomeField($value): ?ArtScene
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
