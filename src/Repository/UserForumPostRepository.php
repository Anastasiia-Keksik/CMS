<?php

namespace App\Repository;

use App\Entity\UserForumPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumPost[]    findAll()
 * @method UserForumPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumPost::class);
    }

//     /**
//      * @return UserForumPost[] Returns an array of ForumPost objects
//      * @return ForumPost[] Returns an array of ForumPost objects
//      */

//    public function findByExampleField($userId, $limit)
//    {
//        $this->createQueryBuilder('p')
//            ->from('App:UserForumPost', 'UserPosts')
//            ->andWhere('p.Author = :user')
//            ->setParameter('user', $userId)
//            ->orderBy('p.createdAt', 'DESC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /*
    public function findOneBySomeField($value): ?ForumPost
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
