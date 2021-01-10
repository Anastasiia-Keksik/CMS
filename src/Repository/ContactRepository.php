<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

     /**
      * @return Contact[] Returns an array of Contact objects
      */

    public function findContacts($value)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT id as id, account_targets_id as contact FROM contact WHERE account_source_id = :value
                UNION ALL 
                SELECT id as id, account_source_id as contact FROM contact WHERE account_targets_id = :value";
        $stmt = $conn->prepare($sql);
        $stmt -> execute(array('value'=>$value));

        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Contact
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
