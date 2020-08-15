<?php

namespace App\Repository;

use App\Entity\AccountSignature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountSignature|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountSignature|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountSignature[]    findAll()
 * @method AccountSignature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountSignatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountSignature::class);
    }

    // /**
    //  * @return AccountSignature[] Returns an array of AccountSignature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountSignature
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
