<?php

namespace App\Repository;

use App\Entity\Circles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Circles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Circles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Circles[]    findAll()
 * @method Circles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CirclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Circles::class);
    }

    public function checkForName($name, $user)
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.Name = :string')
            ->andWhere('c.User = :user')
            ->setParameter('string', $name)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return Circles[] Returns an array of Circles objects
     */

    public function getCircles($user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.User = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Circles
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
