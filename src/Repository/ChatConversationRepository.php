<?php

namespace App\Repository;

use App\Entity\ChatConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatConversation[]    findAll()
 * @method ChatConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatConversation::class);
    }

    public function findConversationByParticipants($otherUserId, $myId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select($qb->expr()->count('p.conversation'))
            ->innerJoin('c.chatParticipants', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.user', ':me'),
                    $qb->expr()->eq('p.user', ':otherUser')
                )
            )
            ->andWhere('c.active = 1')
            ->groupBy('p.conversation')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversation'),
                    2
                )
            )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUserId
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationsByUser2($userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
        select('c.id as conversationId', 'c.active as activated',
            'lm.createdAt')
            ->innerJoin('c.chatParticipants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessageId', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->where('meUser.id = :user')
            ->andWhere('c.active = 1')
            ->andWhere('me.activeStatus = 1')
            ->setParameter('user', $userId)
            ->orderBy('lm.createdAt', 'DESC')
        ;
        $result = $qb->getQuery()->getResult();
        return ($result)? $result : null ;
    }



    public function findConversationsByUser($userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
        select('otherUser.username', 'otherUser.avatarFileName', 'otherUser.firstName', 'otherUser.lastName',
            'otherUser.Occupation', 'otherUser.email', 'c.id as conversationId', 'c.active as activated', 'lm.content',
            'lm.createdAt')
            ->innerJoin('c.chatParticipants', 'p', Join::WITH, $qb->expr()->neq('p.user', ':user'))
            ->innerJoin('c.chatParticipants', 'me', Join::WITH, $qb->expr()->eq('me.user', ':user'))
            ->leftJoin('c.lastMessageId', 'lm')
            ->innerJoin('me.user', 'meUser')
            ->innerJoin('p.user', 'otherUser')
            ->where('meUser.id = :user')
            ->andWhere('c.active = 1')
            ->andWhere('me.activeStatus = 1')
            ->setParameter('user', $userId)
            ->orderBy('lm.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function checkIfUserisParticipant($conversationId, $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->innerJoin('c.chatParticipants', 'p')
            ->where('c.id = :conversationId')
            ->andWhere(
                $qb->expr()->eq('p.user', ':userId')
            )
            ->andWhere('p.activeStatus = 1')
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
