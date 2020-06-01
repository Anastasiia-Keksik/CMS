<?php

namespace App\Entity;

use App\Repository\ForumPostConversationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumPostConversationRepository::class)
 */
class ForumPostConversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="forumPostConversations")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ForumPost::class, inversedBy="forumPostConversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Post;

    /**
     * @ORM\Column(type="boolean")
     */
    private $solfDelete = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Account
    {
        return $this->author;
    }

    public function setAuthor(?Account $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPost(): ?ForumPost
    {
        return $this->Post;
    }

    public function setPost(?ForumPost $Post): self
    {
        $this->Post = $Post;

        return $this;
    }

    public function getSolfDelete(): ?bool
    {
        return $this->solfDelete;
    }

    public function setSolfDelete(bool $solfDelete): self
    {
        $this->solfDelete = $solfDelete;

        return $this;
    }
}
