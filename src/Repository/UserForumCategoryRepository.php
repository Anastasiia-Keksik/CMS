<?php

namespace App\Repository;

use App\Entity\UserForumCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumCategory[]    findAll()
 * @method UserForumCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumCategory::class);
    }

    /**
     * @return UserForumCategory[] Returns an array of ForumCategory objects
     */
    public function takeCategoriesByOrderValue($forumid)
    {

        return $this->createQueryBuilder('t')
            ->select('t.name', 't.OrderValue', 't.id')
            ->where('t.IsItUserPrivateForum = :forum')
            ->setParameter(':forum', $forumid)
            ->orderBy('t.OrderValue', 'ASC')
            ->getQuery()
            ->getResult();

    }

    /**
     * @return UserForumCategory[] Returns an array of ForumCategory objects
     */
    public function takeAllInfoFromCategoriesByOrderValue($forumid)
    {

        return $this->createQueryBuilder('t')
            ->where('t.IsItUserPrivateForum = :forum')
            ->setParameter(':forum', $forumid)
            ->orderBy('t.OrderValue', 'ASC')
            ->getQuery()
            ->getResult();

    }
}