<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="mine_groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $leader;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\OneToMany(targetEntity=GroupCategory::class, mappedBy="groupID", orphanRemoval=true)
     */
    private $groupCategories;

    /**
     * @ORM\OneToMany(targetEntity=SocialPost::class, mappedBy="groupID")
     */
    private $socialPosts;

    public function __construct()
    {
        $this->groupCategories = new ArrayCollection();
        $this->socialPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getLeader(): ?Account
    {
        return $this->leader;
    }

    public function setLeader(?Account $leader): self
    {
        $this->leader = $leader;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    /**
     * @return Collection|GroupCategory[]
     */
    public function getGroupCategories(): Collection
    {
        return $this->groupCategories;
    }

    public function addGroupCategory(GroupCategory $groupCategory): self
    {
        if (!$this->groupCategories->contains($groupCategory)) {
            $this->groupCategories[] = $groupCategory;
            $groupCategory->setGroupID($this);
        }

        return $this;
    }

    public function removeGroupCategory(GroupCategory $groupCategory): self
    {
        if ($this->groupCategories->removeElement($groupCategory)) {
            // set the owning side to null (unless already changed)
            if ($groupCategory->getGroupID() === $this) {
                $groupCategory->setGroupID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SocialPost[]
     */
    public function getSocialPosts(): Collection
    {
        return $this->socialPosts;
    }

    public function addSocialPost(SocialPost $socialPost): self
    {
        if (!$this->socialPosts->contains($socialPost)) {
            $this->socialPosts[] = $socialPost;
            $socialPost->setGroupID($this);
        }

        return $this;
    }

    public function removeSocialPost(SocialPost $socialPost): self
    {
        if ($this->socialPosts->removeElement($socialPost)) {
            // set the owning side to null (unless already changed)
            if ($socialPost->getGroupID() === $this) {
                $socialPost->setGroupID(null);
            }
        }

        return $this;
    }
}
