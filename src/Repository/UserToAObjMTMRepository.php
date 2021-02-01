<?php

namespace App\Repository;

use App\Entity\UserToAObjMTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserToAObjMTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserToAObjMTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserToAObjMTM[]    findAll()
 * @method UserToAObjMTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserToAObjMTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToAObjMTM::class);
    }

    // /**
    //  * @return UserToAObjMTM[] Returns an array of UserToAObjMTM objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserToAObjMTM
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
