<?php

namespace App\Repository;

use App\Entity\UserPrintScreens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPrintScreens|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPrintScreens|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPrintScreens[]    findAll()
 * @method UserPrintScreens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPrintScreensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPrintScreens::class);
    }

    public function findUserPrintTry($user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user = :val')
            ->setParameter('val', $user)
            ->orderBy('u.lastPrintAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserPrintScreens
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
