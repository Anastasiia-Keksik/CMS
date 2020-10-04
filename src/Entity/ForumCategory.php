<?php

namespace App\Entity;

use App\Repository\ForumCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ForumCategoryRepository::class)
 */
class ForumCategory
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
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
     * @ORM\OneToMany(targetEntity=ForumForum::class, mappedBy="Category", fetch="EAGER")
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

        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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
     * @return Collection|ForumForum[]
     */
    public function getForumForum(): Collection
    {
        return $this->forumForum;
    }

    public function addForumForum(ForumForum $forum): self
    {
        if (!$this->forumForum->contains($forum)) {
            $this->forumForum[] = $forum;
            $forum->setCategory($this);
        }

        return $this;
    }

    public function removeForum(ForumForum $forum): self
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

    public function removeForumForum(ForumForum $forumForum): self
    {
        if ($this->forumForum->contains($forumForum)) {
            $this->forumForum->removeElement($forumForum);
            // set the owning side to null (unless already changed)
            if ($forumForum->getCategory() === $this) {
                $forumForum->setCategory(null);
            }
        }

        return $this;
    }
}
