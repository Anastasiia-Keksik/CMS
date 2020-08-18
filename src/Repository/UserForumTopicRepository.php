<?php

namespace App\Repository;

use App\Entity\UserForumTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumTopic[]    findAll()
 * @method UserForumTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumTopic::class);
    }

    /**
     * @return UserForumTopic[] Returns an array of ForumTopic objects
     */

    public function findLast10()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTopicsWithPagination($forumid): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.forum = :forum')
            ->setParameter('forum', $forumid)
            ->andWhere('s.softDelete = 0')
            ->orderBy('s.sticky', 'DESC')
            ->addOrderBy('s.createdAt', 'DESC')
            ;
    }

    /*
    public function findOneBySomeField($value): ?ForumTopic
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
