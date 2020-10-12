<?php

namespace App\Entity;

use App\Repository\ChatConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ChatConversationRepository::class)
 * @ORM\Table(indexes={@Index(name="last_message_id_index", columns={"last_message_id"})})
 */
class ChatConversation
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=ChatMessage::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $chatMessages;

    /**
     * @ORM\OneToOne(targetEntity=ChatMessage::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="last_message_id", referencedColumnName="id")
     */
    private $lastMessageId;

    /**
     * @ORM\OneToMany(targetEntity=ChatParticipant::class, mappedBy="conversation")
     */
    private $chatParticipants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function __construct()
    {
        $this->chatMessages = new ArrayCollection();
        $this->chatParticipants = new ArrayCollection();

        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setConversation($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->removeElement($chatMessage);
            // set the owning side to null (unless already changed)
            if ($chatMessage->getConversation() === $this) {
                $chatMessage->setConversation(null);
            }
        }

        return $this;
    }

    public function getLastMessageId(): ?ChatMessage
    {
        return $this->lastMessageId;
    }

    public function setLastMessageId(?ChatMessage $lastMessageId): self
    {
        $this->lastMessageId = $lastMessageId;

        return $this;
    }

    /**
     * @return Collection|ChatParticipant[]
     */
    public function getChatParticipants(): Collection
    {
        return $this->chatParticipants;
    }

    public function addChatParticipant(ChatParticipant $chatParticipant): self
    {
        if (!$this->chatParticipants->contains($chatParticipant)) {
            $this->chatParticipants[] = $chatParticipant;
            $chatParticipant->setConversation($this);
        }

        return $this;
    }

    public function removeChatParticipant(ChatParticipant $chatParticipant): self
    {
        if ($this->chatParticipants->contains($chatParticipant)) {
            $this->chatParticipants->removeElement($chatParticipant);
            // set the owning side to null (unless already changed)
            if ($chatParticipant->getConversation() === $this) {
                $chatParticipant->setConversation(null);
            }
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
