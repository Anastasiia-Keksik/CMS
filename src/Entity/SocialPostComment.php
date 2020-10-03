<?php

namespace App\Entity;

use App\Repository\SocialPostCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=SocialPostCommentRepository::class)
 */
class SocialPostComment
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="socialPostComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $softDelete;

    /**
     * @ORM\ManyToOne(targetEntity=SocialPost::class, inversedBy="socialPostComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Post;

    /**
     * @ORM\ManyToOne(targetEntity=SocialPostComment::class, inversedBy="socialPostComments")
     */
    private $underAnotherComment;

    /**
     * @ORM\OneToMany(targetEntity=SocialPostComment::class, mappedBy="underAnotherComment")
     */
    private $socialPostComments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ModifiedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    public function __construct()
    {
        $this->socialPostComments = new ArrayCollection();
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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

    public function getAuthor(): ?Account
    {
        return $this->author;
    }

    public function setAuthor(?Account $author): self
    {
        $this->author = $author;

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

    public function getPost(): ?SocialPost
    {
        return $this->Post;
    }

    public function setPost(?SocialPost $Post): self
    {
        $this->Post = $Post;

        return $this;
    }

    public function getUnderAnotherComment(): ?self
    {
        return $this->underAnotherComment;
    }

    public function setUnderAnotherComment(?self $underAnotherComment): self
    {
        $this->underAnotherComment = $underAnotherComment;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSocialPostComments(): Collection
    {
        return $this->socialPostComments;
    }

    public function addSocialPostComment(self $socialPostComment): self
    {
        if (!$this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments[] = $socialPostComment;
            $socialPostComment->setUnderAnotherComment($this);
        }

        return $this;
    }

    public function removeSocialPostComment(self $socialPostComment): self
    {
        if ($this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments->removeElement($socialPostComment);
            // set the owning side to null (unless already changed)
            if ($socialPostComment->getUnderAnotherComment() === $this) {
                $socialPostComment->setUnderAnotherComment(null);
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
        return $this->ModifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $ModifiedAt): self
    {
        $this->ModifiedAt = $ModifiedAt;

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
}
