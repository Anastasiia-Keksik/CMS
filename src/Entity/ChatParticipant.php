<?php

namespace App\Entity;

use App\Repository\ChatParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ChatParticipantRepository::class)
 * @ORM\Table(indexes={@Index(name="created_at_index", columns={"created_at"})})
 * @ORM\HasLifecycleCallbacks()
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?int
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
}
