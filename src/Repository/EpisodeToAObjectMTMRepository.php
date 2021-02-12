<?php

namespace App\Repository;

use App\Entity\EpisodeToAObjectMTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpisodeToAObjectMTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeToAObjectMTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeToAObjectMTM[]    findAll()
 * @method EpisodeToAObjectMTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeToAObjectMTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeToAObjectMTM::class);
    }

    // /**
    //  * @return EpisodeToAObjectMTM[] Returns an array of EpisodeToAObjectMTM objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EpisodeToAObjectMTM
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
