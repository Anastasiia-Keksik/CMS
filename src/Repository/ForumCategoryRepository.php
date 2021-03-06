<?php

namespace App\Repository;

use App\Entity\ForumCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCategory[]    findAll()
 * @method ForumCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumCategory::class);
    }

     /**
      * @return ForumCategory[] Returns an array of ForumCategory objects
      */
    public function takeCategoriesByOrderValue($user = NULL){
        if ($user == NULL)
        {
            return $this->createQueryBuilder('t')
                ->select('t.name', 't.OrderValue')
                ->where('t.IsItUserPrivateForum IS NULL')
                ->orderBy('t.OrderValue','ASC')
                ->getQuery()
                ->getResult();
        }else{
            return $this->createQueryBuilder('t')
                ->select('t.name', 't.OrderValue')
                ->where('t.IsItUserPrivateForum = :user')
                ->setParameter(':user', $user)
                ->orderBy('t.OrderValue','ASC')
                ->getQuery()
                ->getResult();
        }
    }
//    public function takeCategories($user = NULL)
//    {
//        $cat = $this->createQueryBuilder('c')
//            ->select('')
//            ->Where()
//        return $cat;
//    }
    // /**
    //  * @return ForumCategory[] Returns an array of ForumCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumCategory
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
