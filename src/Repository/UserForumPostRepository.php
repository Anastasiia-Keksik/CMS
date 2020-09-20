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

    /**
     * @return UserForumPost[] Returns an array of ForumTopic objects
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

    /**
     * @return UserForumPost[] Returns an array of ForumTopic objects
     */
    public function findPostsForThreadWithPagination($topicid)
    {
        return $this->createQueryBuilder('p')
        ->where('p.ForumTopic = :topic')
        ->setParameter('topic', $topicid)
        ->addOrderBy('p.createdAt', 'ASC')
        ;
    }

    /**
     * @return UserForumPost[] Returns an array of ForumTopic objects
     */
    public function findPostsMinePagination($topicid, $pageid = 0, $postsAmount = 10)
    {
        $offset = $pageid * $postsAmount;

        return $this->createQueryBuilder('p')
        ->where('p.ForumTopic = :topic')
        ->setParameter('topic', $topicid)
        ->addOrderBy('p.createdAt', 'ASC')
        ->setMaxResults($postsAmount)
        ->setFirstResult($offset)
        ->getQuery()
        ->getResult()
        ;
    }

    public function threadPostsCount($threadid){
        return $this->createQueryBuilder('c')
            ->select('COUNT(\'id\')')
            ->where('c.ForumTopic = :thread')
            ->setParameter('thread', $threadid)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function lastPost($forumid){
        return $this->createQueryBuilder('c')
            ->join('c.ForumTopic', 'topic', 'WITH', 'topic.forum = :forum')
            ->setParameter('forum', $forumid)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
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
