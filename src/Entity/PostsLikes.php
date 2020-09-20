<?php

namespace App\Entity;

use App\Repository\PostsLikesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostsLikesRepository::class)
 */
class PostsLikes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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

    public function getId(): ?int
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
