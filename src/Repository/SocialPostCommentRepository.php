<?php

namespace App\Repository;

use App\Entity\SocialPostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialPostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialPostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialPostComment[]    findAll()
 * @method SocialPostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialPostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialPostComment::class);
    }

    public function lastComment($userID)
    {
        return$this->createQueryBuilder('c')
            ->where('c.author = :val')
            ->setParameter('val', $userID)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @return SocialPostComment[] Returns an array of SocialPostComment objects
     */
    public function findNewestComments($postid, $max)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->andWhere('c.softDelete != 1')
            ->andWhere('c.underAnotherComment is null')
            ->andWhere('c.Post = :postid')
            ->setParameter('postid', $postid)
            ->orderBy('c.likes', 'DESC')
            ->addOrderBy('c.createdAt', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return SocialPostComment[] Returns an array of SocialPost objects
     */

    public function calendarGet($user, $start, $end)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :val')
            ->andWhere('c.createdAt >= :start')
            ->andWhere('c.createdAt <= :end')
            ->setParameter('val', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return SocialPostComment[] Returns an array of SocialPostComment objects
     */
    public function findNewestCommentConversation($value, $commentId)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->andWhere('c.softDelete != 1')
            ->andWhere('c.underAnotherComment = :val')
            ->setParameter('val',$commentId)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCommentsBySomething($howmuch, $orderBy, $startFrom, $pagination, $postid)
    {
        $pagination = ($pagination-1) * 10 + $startFrom;
        if ($pagination < 0) { $pagination = 0; }
        if ($orderBy == "likes"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment is null')
                ->andWhere('c.Post = :postid')
                ->setParameter('postid', $postid)
                ->orderBy('c.likes', 'DESC')
                ->addOrderBy('c.createdAt', 'DESC')
                ->setMaxResults(10)
                ->setFirstResult($pagination)
                ->getQuery()
                ->getResult()
                ;
        }elseif($orderBy == "newest"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment is null')
                ->andWhere('c.Post = :postid')
                ->setParameter('postid', $postid)
                ->addOrderBy('c.createdAt', 'DESC')
                ->setMaxResults($howmuch)
                ->setFirstResult($pagination)
                ->getQuery()
                ->getResult()
                ;
        }
    }

    public function findCommentConversationBySomething($howmuch, $orderBy, $startFrom, $pagination, $commentid)
    {
        $pagination = ($pagination-1) * 10 + $startFrom;
        if ($pagination < 0) { $pagination = 0; }
        if ($orderBy == "likes"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment = :commentid')
                ->setParameter('commentid', $commentid)
                ->orderBy('c.likes', 'DESC')
                ->addOrderBy('c.createdAt', 'DESC')
                ->setFirstResult($pagination)
                ->setMaxResults($howmuch)
                ->getQuery()
                ->getResult()
                ;
        }elseif($orderBy == "newest"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment = :commentid')
                ->setParameter('commentid', $commentid)
                ->addOrderBy('c.createdAt', 'DESC')
                ->setMaxResults($howmuch)
                ->setFirstResult($pagination)
                ->getQuery()
                ->getResult()
                ;
        }
    }

    public function countAllComments($post)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp.id)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.Post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countMainComments($post)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp.id)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.underAnotherComment is null')
            ->andWhere('ccp.Post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countCommentsInConversation($commentid)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp.id)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.underAnotherComment = :comment')
            ->setParameter('comment', $commentid)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?SocialPostComment
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
