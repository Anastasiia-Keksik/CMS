<?php

namespace App\Repository;

use App\Entity\EpisodeToArtSceneMTM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpisodeToArtSceneMTM|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeToArtSceneMTM|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeToArtSceneMTM[]    findAll()
 * @method EpisodeToArtSceneMTM[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeToArtSceneMTMRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeToArtSceneMTM::class);
    }

    // /**
    //  * @return EpisodeToArtSceneMTM[] Returns an array of EpisodeToArtSceneMTM objects
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
    public function findOneBySomeField($value): ?EpisodeToArtSceneMTM
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
