<?php

namespace App\Repository;

use App\Entity\ChatMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatMessage[]    findAll()
 * @method ChatMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatMessage::class);
    }

    public function findMessageByConversationId($conversationId)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.conversation = :conversationId')
            ->setParameter('conversationId', $conversationId)
            ->orderBy('m.createdAt', "DESC")
            ->setMaxResults(60)
        ;

        return $qb->getQuery()->getResult();
    }


    public function findMessagesPagination($conversationId, int $page)
    {
        $page = $page * 60;
        $page = $page + 1;

        $qb = $this->createQueryBuilder('m');
        $qb->where('m.conversation = :conversationId')
            ->setParameter('conversationId', $conversationId)
            ->orderBy('m.createdAt', "DESC")
            ->setMaxResults(60)
            ->setFirstResult($page)
        ;

        return $qb->getQuery()->getResult();
    }
}
