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

     /**
      * @return ComicEpisode[] Returns an array of ComicEpisode objects
      */

    public function get10Episodes($comicid, $page)
    {
        $offset = ($page-1)*10;

        return $this->createQueryBuilder('c')
            ->andWhere('c.comic = :id')
            ->andWhere('c.published = 1')
            ->setParameter('id', $comicid)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCountEpisodesPublished($comicid){

        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.comic = :id')
            ->andWhere('c.published = 1')
            ->setParameter('id', $comicid)
            ->getQuery()
            ->getScalarResult()
            ;
    }

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
