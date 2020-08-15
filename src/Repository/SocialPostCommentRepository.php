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
            ->addOrderBy('c.id', 'ASC')
            ->setMaxResults($max)
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
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCommentsBySomething($howmuch, $orderBy, $startFrom, $pagination, $postid)
    {
        $pagination = ($pagination-1) * 10 + $startFrom;
        if ($orderBy == "likes"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment is null')
                ->andWhere('c.Post = :postid')
                ->setParameter('postid', $postid)
                ->orderBy('c.likes', 'DESC')
                ->addOrderBy('c.id', 'ASC')
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
                ->addOrderBy('c.id', 'DESC')
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
        if ($orderBy == "likes"){
            return $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.softDelete != 1')
                ->andWhere('c.underAnotherComment = :commentid')
                ->setParameter('commentid', $commentid)
                ->orderBy('c.likes', 'DESC')
                ->addOrderBy('c.id', 'DESC')
                ->getFirstResult($pagination)
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
                ->addOrderBy('c.id', 'DESC')
                ->setMaxResults($howmuch)
                ->getFirstResult($pagination)
                ->getQuery()
                ->getResult()
                ;
        }
    }

    public function countAllComments($post)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.Post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countMainComments($post)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.underAnotherComment is null')
            ->andWhere('ccp.Post = :val')
            ->setParameter('val', $post)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countCommentsInConversation($commentid)
    {
        return $this->createQueryBuilder('ccp')
            ->select('COUNT(ccp)')
            ->andWhere('ccp.softDelete != 1')
            ->andWhere('ccp.underAnotherComment = :comment')
            ->setParameter('comment', $commentid)
            ->getQuery()
            ->getResult()
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
