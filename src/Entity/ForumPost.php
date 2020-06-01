<?php

namespace App\Entity;

use App\Repository\ForumPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumPostRepository::class)
 */
class ForumPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="forumPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=ForumPostConversation::class, mappedBy="Post", orphanRemoval=true)
     */
    private $forumPostConversations;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $softDelete = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity=ForumTopic::class, inversedBy="forumPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ForumTopic;


    public function __construct()
    {
        $this->forumPostConversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Account
    {
        return $this->Author;
    }

    public function setAuthor(?Account $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|ForumPostConversation[]
     */
    public function getForumPostConversations(): Collection
    {
        return $this->forumPostConversations;
    }

    public function addForumPostConversation(ForumPostConversation $forumPostConversation): self
    {
        if (!$this->forumPostConversations->contains($forumPostConversation)) {
            $this->forumPostConversations[] = $forumPostConversation;
            $forumPostConversation->setPost($this);
        }

        return $this;
    }

    public function removeForumPostConversation(ForumPostConversation $forumPostConversation): self
    {
        if ($this->forumPostConversations->contains($forumPostConversation)) {
            $this->forumPostConversations->removeElement($forumPostConversation);
            // set the owning side to null (unless already changed)
            if ($forumPostConversation->getPost() === $this) {
                $forumPostConversation->setPost(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getSoftDelete(): ?bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(?bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getForumTopic(): ?ForumTopic
    {
        return $this->ForumTopic;
    }

    public function setForumTopic(?ForumTopic $ForumTopic): self
    {
        $this->ForumTopic = $ForumTopic;

        return $this;
    }

}
