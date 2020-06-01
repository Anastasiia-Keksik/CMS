<?php

namespace App\Repository;

use App\Entity\UserPrivateForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPrivateForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPrivateForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPrivateForum[]    findAll()
 * @method UserPrivateForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPrivateForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPrivateForum::class);
    }

    // /**
    //  * @return UserPrivateForum[] Returns an array of UserPrivateForum objects
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
    public function findOneBySomeField($value): ?UserPrivateForum
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
