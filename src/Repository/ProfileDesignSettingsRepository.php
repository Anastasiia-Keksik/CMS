<?php

namespace App\Repository;

use App\Entity\ProfileDesignSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfileDesignSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileDesignSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileDesignSettings[]    findAll()
 * @method ProfileDesignSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileDesignSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileDesignSettings::class);
    }

    // /**
    //  * @return ProfileDesignSettings[] Returns an array of ProfileDesignSettings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfileDesignSettings
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
