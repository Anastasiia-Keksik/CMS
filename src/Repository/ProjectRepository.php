<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

     /**
      * @return Project[] Returns an array of Project objects
      */
    public function findComicProjects($user)
    {
        //get all
        return $this->createQueryBuilder('p')
            ->andWhere('ac.User = :val1')
            ->leftJoin('p.Account', 'ac')
            ->leftJoin('p.Comic', 'c')
            ->leftJoin('p.ComicEpisode', 'ce')
            ->setParameter('val1', $user)
            ->orderBy('c.createdAt', 'DESC')
            ->addOrderBy('ce.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Project
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
