<?php

namespace App\Repository;

use App\Entity\UserForumRanks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserForumRanks|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserForumRanks|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserForumRanks[]    findAll()
 * @method UserForumRanks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserForumRanksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserForumRanks::class);
    }

    // /**
    //  * @return UserForumRanks[] Returns an array of UserForumRanks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

     /**
     * @return UserForumRanks[] Returns an array of UserForumRanks objects
     */
    public function findLastOne($forum)
    {
        return $this->createQueryBuilder('u')
            ->select('MAX(u.finish)')
            ->andWhere('u.forum = :val')
            ->setParameter('val', $forum)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
