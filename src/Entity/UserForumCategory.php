<?php

namespace App\Entity;

use App\Repository\UserForumCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserForumCategoryRepository::class)
 */
class UserForumCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $OrderValue;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hidden = false;

    /**
     * @ORM\OneToMany(targetEntity=UserForumForum::class, mappedBy="Category")
     * @ORM\OrderBy({"OrderValue" = "ASC"})
     */
    private $forumForum;

    /**
     * @ORM\ManyToOne(targetEntity=UserPrivateForum::class, inversedBy="forumCategories")
     */
    private $IsItUserPrivateForum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    public function __construct()
    {
        $this->forumForum = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOrderValue(): ?int
    {
        return $this->OrderValue;
    }

//    public function getByOrderValue()
//    {
//        $criteria = Criteria::create()
//            ->orderBy(['OrderValue' => 'ASC']);
//
//        return $this->OrderValue->matching($criteria);
//    }

    public function setOrderValue(int $OrderValue): self
    {
        $this->OrderValue = $OrderValue;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(?bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection|UserForumForum[]
     */
    public function getForumForum(): Collection
    {
        return $this->forumForum;
    }

    public function addForumForum(UserForumForum $forum): self
    {
        if (!$this->forumForum->contains($forum)) {
            $this->forumForum[] = $forum;
            $forum->setCategory($this);
        }

        return $this;
    }

    public function removeForum(UserForumForum $forum): self
    {
        if ($this->forumForum->contains($forum)) {
            $this->forumForum->removeElement($forum);
            // set the owning side to null (unless already changed)
            if ($forum->getCategory() === $this) {
                $forum->setCategory(null);
            }
        }

        return $this;
    }

    public function getIsItUserPrivateForum(): ?UserPrivateForum
    {
        return $this->IsItUserPrivateForum;
    }

    public function setIsItUserPrivateForum(?UserPrivateForum $IsItUserPrivateForum): self
    {
        $this->IsItUserPrivateForum = $IsItUserPrivateForum;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
}
