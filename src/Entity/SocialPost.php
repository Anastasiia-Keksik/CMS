<?php

namespace App\Entity;

use App\Repository\SocialPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=SocialPostRepository::class)
 */
class SocialPost
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

    /**
     * @ORM\OneToMany(targetEntity=Favourites::class, mappedBy="SocialPost")
     */
    private $yes;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $BGcolor;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $BGopacity;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="socialPosts")
     */
    private $groupID;

    /**
     * @ORM\ManyToOne(targetEntity=GroupCategory::class, inversedBy="socialPosts")
     */
    private $GroupCategory;

    public function __construct()
    {
        $this->socialPostComments = new ArrayCollection();

        $this->id = Uuid::uuid4();
        $this->yes = new ArrayCollection();
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

    /**
     * @return Collection|Favourites[]
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Favourites $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setSocialPost($this);
        }

        return $this;
    }

    public function removeYe(Favourites $ye): self
    {
        if ($this->yes->contains($ye)) {
            $this->yes->removeElement($ye);
            // set the owning side to null (unless already changed)
            if ($ye->getSocialPost() === $this) {
                $ye->setSocialPost(null);
            }
        }

        return $this;
    }

    public function getBGcolor(): ?string
    {
        return $this->BGcolor;
    }

    public function setBGcolor(?string $BGcolor): self
    {
        $this->BGcolor = $BGcolor;

        return $this;
    }

    public function getBGopacity(): ?float
    {
        return $this->BGopacity;
    }

    public function setBGopacity(?float $BGopacity): self
    {
        $this->BGopacity = $BGopacity;

        return $this;
    }

    public function getGroupID(): ?Group
    {
        return $this->groupID;
    }

    public function setGroupID(?Group $groupID): self
    {
        $this->groupID = $groupID;

        return $this;
    }

    public function getGroupCategory(): ?GroupCategory
    {
        return $this->GroupCategory;
    }

    public function setGroupCategory(?GroupCategory $GroupCategory): self
    {
        $this->GroupCategory = $GroupCategory;

        return $this;
    }
}
