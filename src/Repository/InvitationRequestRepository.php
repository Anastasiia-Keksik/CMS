<?php

namespace App\Repository;

use App\Entity\InvitationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvitationRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvitationRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvitationRequest[]    findAll()
 * @method InvitationRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvitationRequest::class);
    }

    public function checkIfExist($inviting, $invited)
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->andWhere('i.WhoInvite = :inviting')
            ->andWhere('i.WhoInvited = :invited')
            ->setParameter('inviting', $inviting)
            ->setParameter('invited', $invited)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return InvitationRequest[] Returns an array of InvitationRequest objects
     */

    public function findLast10($user)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.WhoInvited = :invited')
            ->leftJoin('i.WhoInvite', 'in')
            ->setParameter('invited', $user)
            ->setMaxResults(10)
            ->orderBy('i.CreatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?InvitationRequest
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
