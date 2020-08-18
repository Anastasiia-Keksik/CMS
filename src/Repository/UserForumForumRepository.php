<?php

namespace App\Repository;

use App\Entity\UserForumForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumForum[]    findAll()
 * @method UserForumForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumForum::class);
    }

    public function takeForumsByOrderValue($category)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.Name', 't.OrderValue')
            ->where('t.Category = :val')
            ->setParameter(':val', $category)
            ->orderBy('t.OrderValue','ASC')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return ForumForum[] Returns an array of ForumForum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumForum
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
