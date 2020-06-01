<?php

namespace App\Repository;

use App\Entity\ForumPostConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumPostConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumPostConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumPostConversation[]    findAll()
 * @method ForumPostConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumPostConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumPostConversation::class);
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
