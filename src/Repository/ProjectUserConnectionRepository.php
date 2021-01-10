<?php

namespace App\Repository;

use App\Entity\ProjectUserConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectUserConnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectUserConnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectUserConnection[]    findAll()
 * @method ProjectUserConnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectUserConnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectUserConnection::class);
    }

    public function countMine($Pid, $Uid){
        return $this->createQueryBuilder('pu')
            ->select('count(pu.id)')
            ->andWhere('pu.Project = :id')
            ->andWhere('pu.User = :user')
            ->setParameter('id', $Pid)
            ->setParameter('user', $Uid)
            ->getQuery()
            ->getSingleScalarResult()
            ;

    }


    /*
    public function findOneBySomeField($value): ?ProjectUserConnection
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
