<?php

namespace App\Entity;

use App\Repository\ChatParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ChatParticipantRepository::class)
 */
class ChatParticipant
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $messagesReadAt;

    /**
     * @ORM\ManyToOne(targetEntity=ChatConversation::class, inversedBy="chatParticipants")
     */
    private $conversation;

    /**
     * @ORM\Column(type="smallint")
     */
    private $activeStatus;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(?Account $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessagesReadAt(): ?\DateTimeInterface
    {
        return $this->messagesReadAt;
    }

    public function setMessagesReadAt(?\DateTimeInterface $messagesReadAt): self
    {
        $this->messagesReadAt = $messagesReadAt;

        return $this;
    }

    public function getConversation(): ?ChatConversation
    {
        return $this->conversation;
    }

    public function setConversation(?ChatConversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getActiveStatus(): ?int
    {
        return $this->activeStatus;
    }

    public function setActiveStatus(int $activeStatus): self
    {
        $this->activeStatus = $activeStatus;

        return $this;
    }
}
