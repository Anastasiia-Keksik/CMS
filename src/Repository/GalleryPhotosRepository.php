<?php

namespace App\Repository;

use App\Entity\GalleryPhotos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GalleryPhotos|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryPhotos|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryPhotos[]    findAll()
 * @method GalleryPhotos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryPhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GalleryPhotos::class);
    }

     /**
      * @return GalleryPhotos[] Returns an array of GalleryPhotos objects
      */
    public function findLast9($galleryid)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.galleryId = :val')
            ->setParameter('val', $galleryid)
            ->orderBy('g.uploadedAt', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return GalleryPhotos[] Returns an array of GalleryPhotos objects
     */
    public function takePhotos($galleryid)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.galleryId = :val')
            ->setParameter('val', $galleryid)
            ->orderBy('g.uploadedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return GalleryPhotos[] Returns an array of GalleryPhotos objects
     */
    public function takeAlbumPhotos($albumid)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.album = :val')
            ->setParameter('val', $albumid)
            ->orderBy('g.uploadedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?GalleryPhotos
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
