<?php

namespace App\Entity;

use App\Repository\GroupCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupCategoryRepository::class)
 */
class GroupCategory
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
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="groupCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupID;

    /**
     * @ORM\OneToMany(targetEntity=SocialPost::class, mappedBy="GroupCategory")
     */
    private $socialPosts;

    public function __construct()
    {
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

    public function getGroupID(): ?Group
    {
        return $this->groupID;
    }

    public function setGroupID(?Group $groupID): self
    {
        $this->groupID = $groupID;

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
            $socialPost->setGroupCategory($this);
        }

        return $this;
    }

    public function removeSocialPost(SocialPost $socialPost): self
    {
        if ($this->socialPosts->removeElement($socialPost)) {
            // set the owning side to null (unless already changed)
            if ($socialPost->getGroupCategory() === $this) {
                $socialPost->setGroupCategory(null);
            }
        }

        return $this;
    }
}
