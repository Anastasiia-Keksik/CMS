<?php

namespace App\Repository;

use App\Entity\UnderForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UnderForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnderForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnderForum[]    findAll()
 * @method UnderForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnderForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnderForum::class);
    }

    // /**
    //  * @return UnderForum[] Returns an array of UnderForum objects
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
    public function findOneBySomeField($value): ?UnderForum
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
