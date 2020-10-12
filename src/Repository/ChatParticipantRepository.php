<?php

namespace App\Repository;

use App\Entity\ChatParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatParticipant[]    findAll()
 * @method ChatParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatParticipant::class);
    }

    public function findParticipantByConverstionIdAndUserId($conversationId, $userId)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->
        where(
            $qb->expr()->andX(
                $qb->expr()->eq('p.conversation', ':conversationId'),
                $qb->expr()->neq('p.user', ':userId')
            )
        )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ]);

        return $qb->getQuery()->getResult();
    }


    public function findParticipantsByConverstionIdAndUserId($conversationId, $userId)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->
            select('otherUser.username', 'otherUser.avatarFileName', 'otherUser.firstName', 'otherUser.lastName',
            'otherUser.Occupation', 'otherUser.email')
            ->addSelect('con.id')
            ->where(
            $qb->expr()->andX(
                $qb->expr()->eq('p.conversation', ':conversationId'),
                $qb->expr()->neq('p.user', ':userId')
                )
            )->andWhere('p.activeStatus = 1')
            ->innerJoin('p.user', 'otherUser')
            ->innerJoin('p.conversation', 'con')
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ]);

        return $qb->getQuery()->getResult();
    }
}
