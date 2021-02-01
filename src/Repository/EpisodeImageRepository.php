<?php

namespace App\Repository;

use App\Entity\EpisodeImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpisodeImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpisodeImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpisodeImage[]    findAll()
 * @method EpisodeImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeImage::class);
    }

    // /**
    //  * @return EpisodeImage[] Returns an array of EpisodeImage objects
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
    public function findOneBySomeField($value): ?EpisodeImage
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
