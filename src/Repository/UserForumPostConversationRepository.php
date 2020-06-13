<?php

namespace App\Repository;

use App\Entity\UserForumPostConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumPostConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumPostConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumPostConversation[]    findAll()
 * @method UserForumPostConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumPostConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumPostConversation::class);
    }

    // /**
    //  * @return ForumPostConversation[] Returns an array of ForumPostConversation objects
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
    public function findOneBySomeField($value): ?ForumPostConversation
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
