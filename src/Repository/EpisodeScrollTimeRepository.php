<?php

namespace App\Repository;

use App\Entity\EpisodeScrollTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpisodeScrollTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeScrollTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeScrollTime[]    findAll()
 * @method EpisodeScrollTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeScrollTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeScrollTime::class);
    }

     /**
      * @return EpisodeScrollTime[] Returns an array of EpisodeScrollTime objects
      */

    public function getEpisodeScrollTimes($episodeID)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.Episode = :val')
            ->setParameter('val', $episodeID)
            ->orderBy('e.orderNbr', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    public function getLast($episode): ?EpisodeScrollTime
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.Episode = :val')
            ->setParameter('val', $episode)
            ->setMaxResults(1)
            ->orderBy('e.orderNbr', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
