<?php

namespace App\Repository;

use App\Entity\ForumMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumMembers[]    findAll()
 * @method ForumMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumMembersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumMembers::class);
    }

     /**
      * @return ForumMembers[] Returns an array of ForumMembers objects
      */

    public function findMember($forum, $member)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.Forum = :for')
            ->andWhere('m.Member = :mem')
            ->setParameter('for', $forum)
            ->setParameter('mem', $member)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return ForumMembers[] Returns an array of ForumMembers objects
     */

    public function findAllMembers($forum)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb ->select('m', 'm.Rank', 'm.role', 'u.id', 'u.firstName', 'u.username', 'u.lastName', 'u.lastOnline', 'u.avatarFileName', 'u.BackgroundFileName', 'u.bgPosition')
            ->andWhere('m.Forum = :for')
            ->andWhere('m.pending = 0')
            ->join('m.Member', 'u')
            ->setParameter('for', $forum)
            ->getQuery()
            ->getResult();
    }

    public function findMembersFromLetter($forum, $letter)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->select('m', 'm.Rank', 'm.role',  'u.id', 'u.firstName', 'u.username', 'u.lastName', 'u.lastOnline', 'u.avatarFileName', 'u.BackgroundFileName', 'u.bgPosition')
            ->Where('m.Forum = :for')
            ->andWhere('u.username LIKE :letter')
            ->andWhere('m.pending = 0')
            ->join('m.Member', 'u')
            ->setParameter('for', $forum)
            ->setParameter('letter', $letter . '%')
            ->getQuery()
            ->getResult();
    }

    public function findMembersBySearch($forum, $string)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->select('m', 'm.Rank', 'm.role', 'u.id', 'u.firstName', 'u.username', 'u.lastName', 'u.lastOnline', 'u.avatarFileName', 'u.BackgroundFileName', 'u.bgPosition')
            ->Where('m.Forum = :for')
            ->andWhere('u.username LIKE :letter')
            ->andWhere('m.pending = 0')
            ->join('m.Member', 'u')
            ->setParameter('for', $forum)
            ->setParameter('letter', '%' . $string . '%')
            ->getQuery()
            ->getResult();
    }

        /*
        public function findOneBySomeField($value): ?ForumMembers
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
