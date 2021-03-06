<?php

namespace App\Entity;

use App\Repository\UserForumPostConversationRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserForumPostConversationRepository::class)
 */
class UserForumPostConversation
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="userForumPostConversations")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumPost::class, inversedBy="forumPostConversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Post;

    /**
     * @ORM\Column(type="boolean")
     */
    private $softDelete = false;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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

    public function getSoftDelete(): ?bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }
}
