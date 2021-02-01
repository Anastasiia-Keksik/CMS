<?php

namespace App\Repository;

use App\Entity\EpisodeSounds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpisodeSounds|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeSounds|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeSounds[]    findAll()
 * @method EpisodeSounds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeSoundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeSounds::class);
    }

//    // /**
//    //  * @return EpisodeSounds[] Returns an array of EpisodeSounds objects
//    //  */
//    /*
//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    */


    public function checkForUsedStartPointPosition($episode, $position)
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->andWhere('ce.id = :ep')
            ->andWhere('e.startPoint = :ps')
            ->leftJoin('e.ComicEpisode', 'ce')
            ->setParameter('ep', $episode)
            ->setParameter('ps', $position)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

}
