<?php

namespace App\Security\Voter;

use App\Entity\ChatConversation;
use App\Repository\ChatConversationRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    /**
     * @var ChatConversationRepository
     */
    private $conversationRepository;

    public function __construct(ChatConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    const VIEW = 'view';

    protected function supports(string $attribute, $subject)
    {
        return $attribute == self::VIEW && $subject instanceof ChatConversation;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $result = $this->conversationRepository->checkIfUserisParticipant(
            $subject->getId(),
            $token->getUser()->getId()
        );

        return !!$result;

    }
}