<?php

namespace App\Repository;

use App\Entity\ArtSceneToAObjMTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtSceneToAObjMTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtSceneToAObjMTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtSceneToAObjMTM[]    findAll()
 * @method ArtSceneToAObjMTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtSceneToAObjMTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtSceneToAObjMTM::class);
    }

    // /**
    //  * @return ArtSceneToAObjMTM[] Returns an array of ArtSceneToAObjMTM objects
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
    public function findOneBySomeField($value): ?ArtSceneToAObjMTM
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
