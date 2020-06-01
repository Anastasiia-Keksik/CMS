<?php

namespace App\Repository;

use App\Entity\Passwords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Passwords|null find($id, $lockMode = null, $lockVersion = null)
 * @method Passwords|null findOneBy(array $criteria, array $orderBy = null)
 * @method Passwords[]    findAll()
 * @method Passwords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Passwords::class);
    }

    // /**
    //  * @return Passwords[] Returns an array of Passwords objects
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
    public function findOneBySomeField($value): ?Passwords
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
