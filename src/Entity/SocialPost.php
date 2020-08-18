<?php

namespace App\Entity;

use App\Repository\SocialPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialPostRepository::class)
 */
class SocialPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="socialPosts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $softDelete;

    /**
     * @ORM\OneToMany(targetEntity=SocialPostComment::class, mappedBy="Post", orphanRemoval=true)
     */
    private $socialPostComments;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backgroundFilename;

    public function __construct()
    {
        $this->socialPostComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAccount(): ?Account
    {
        return $this->Account;
    }

    public function setAccount(?Account $Account): self
    {
        $this->Account = $Account;

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

    public function setSoftDelete(bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    /**
     * @return Collection|SocialPostComment[]
     */
    public function getSocialPostComments(): Collection
    {
        return $this->socialPostComments;
    }

    public function addSocialPostComment(SocialPostComment $socialPostComment): self
    {
        if (!$this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments[] = $socialPostComment;
            $socialPostComment->setPost($this);
        }

        return $this;
    }

    public function removeSocialPostComment(SocialPostComment $socialPostComment): self
    {
        if ($this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments->removeElement($socialPostComment);
            // set the owning side to null (unless already changed)
            if ($socialPostComment->getPost() === $this) {
                $socialPostComment->setPost(null);
            }
        }

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

    public function getBackgroundFilename(): ?string
    {
        return $this->backgroundFilename;
    }

    public function setBackgroundFilename(?string $backgroundFilename): self
    {
        $this->backgroundFilename = $backgroundFilename;

        return $this;
    }
}