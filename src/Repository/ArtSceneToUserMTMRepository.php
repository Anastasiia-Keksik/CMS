<?php

namespace App\Repository;

use App\Entity\ArtSceneToUserMTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArtSceneToUserMTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtSceneToUserMTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtSceneToUserMTM[]    findAll()
 * @method ArtSceneToUserMTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtSceneToUserMTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtSceneToUserMTM::class);
    }

    // /**
    //  * @return ArtSceneToUserMTM[] Returns an array of ArtSceneToUserMTM objects
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


    public function findOneBySomeField($user): ?ArtSceneToUserMTM
    {
        return $this->createQueryBuilder('a')
            ->andWhere('au.id = :val')
            ->setParameter('val', $user)
            ->leftJoin('a.User', 'au')
            ->orderBy('a.CreatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
