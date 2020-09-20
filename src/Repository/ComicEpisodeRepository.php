<?php

namespace App\Repository;

use App\Entity\ComicEpisode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComicEpisode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComicEpisode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComicEpisode[]    findAll()
 * @method ComicEpisode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComicEpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComicEpisode::class);
    }

    // /**
    //  * @return ComicEpisode[] Returns an array of ComicEpisode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComicEpisode
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
