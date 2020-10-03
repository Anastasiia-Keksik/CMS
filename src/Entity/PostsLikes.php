<?php

namespace App\Entity;

use App\Repository\PostsLikesRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PostsLikesRepository::class)
 */
class PostsLikes
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="postsLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumPost::class, inversedBy="postsLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Post;
    
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
        return $this->User;
    }

    public function setUser(?Account $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getPost(): ?UserForumPost
    {
        return $this->Post;
    }

    public function setPost(?UserForumPost $Post): self
    {
        $this->Post = $Post;

        return $this;
    }
}
