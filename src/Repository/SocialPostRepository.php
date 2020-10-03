<?php

namespace App\Repository;

use App\Entity\SocialPost;
use App\Entity\SocialPostComment;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialPost[]    findAll()
 * @method SocialPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialPost::class);
    }

    /**
     * @return SocialPost[] Returns an array of SocialPost objects
     */
    
    public function loadNewPosts($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Account = :val')
            ->setParameter('val', $id)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($_SERVER['SOCIAL_POSTS'])
            ->getQuery()
            ->getResult()
        ;
    }

    public function loadNewPostsOffset($id, $offset)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Account = :val')
            ->setParameter('val', $id)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($_SERVER['SOCIAL_POSTS'])
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?SocialPost
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
