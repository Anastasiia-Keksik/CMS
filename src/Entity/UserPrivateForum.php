<?php

namespace App\Entity;

use App\Repository\UserPrivateForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserPrivateForumRepository::class)
 */
class UserPrivateForum
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="userPrivateForum", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserAdmin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

//    /**
//     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="userPrivateForums")
//     */
//    private $Members;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $softDelete;

    /**
     * @ORM\OneToMany(targetEntity=UserForumCategory::class, mappedBy="IsItUserPrivateForum")
     * @ORM\OrderBy({"OrderValue" = "ASC"})
     */
    private $forumCategories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ForumMembers::class, mappedBy="Forum", orphanRemoval=true)
     */
    private $ForumMembers;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=UserForumRanks::class, mappedBy="forum", orphanRemoval=true)
     */
    private $userForumRanks;

    /**
     * @ORM\OneToOne(targetEntity=Themes::class, mappedBy="underForum", cascade={"persist", "remove"})
     */
    private $themes;

    public function __construct()
    {
//        $this->Members = new ArrayCollection();
        $this->forumCategories = new ArrayCollection();

        $this->id = Uuid::uuid4();
        $this->ForumsMembers = new ArrayCollection();
        $this->userForumRanks = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserAdmin(): ?Account
    {
        return $this->UserAdmin;
    }

    public function setUserAdmin(Account $UserAdmin): self
    {
        $this->UserAdmin = $UserAdmin;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

//    /**
//     * @return Collection|Account[]
//     */
//    public function getMembers(): Collection
//    {
//        return $this->Members;
//    }
//
//    public function addMember(Account $member): self
//    {
//        if (!$this->Members->contains($member)) {
//            $this->Members[] = $member;
//        }
//
//        return $this;
//    }
//
//    public function removeMember(Account $member): self
//    {
//        if ($this->Members->contains($member)) {
//            $this->Members->removeElement($member);
//        }
//
//        return $this;
//    }

    public function getSoftDelete(): ?bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(?bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    /**
     * @return Collection|UserForumCategory[]
     */
    public function getForumCategories(): Collection
    {
        return $this->forumCategories;
    }

    public function addForumCategory(UserForumCategory $forumCategory): self
    {
        if (!$this->forumCategories->contains($forumCategory)) {
            $this->forumCategories[] = $forumCategory;
            $forumCategory->setIsItUserPrivateForum($this);
        }

        return $this;
    }

    public function removeForumCategory(UserForumCategory $forumCategory): self
    {
        if ($this->forumCategories->contains($forumCategory)) {
            $this->forumCategories->removeElement($forumCategory);
            // set the owning side to null (unless already changed)
            if ($forumCategory->getIsItUserPrivateForum() === $this) {
                $forumCategory->setIsItUserPrivateForum(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ForumMembers[]
     */
    public function getForumMembers(): Collection
    {
        return $this->ForumMembers;
    }

    public function addForumMembers(ForumMembers $forumMembers): self
    {
        if (!$this->ForumMembers->contains($forumMembers)) {
            $this->ForumMembers[] = $forumMembers;
            $forumMembers->setForum($this);
        }

        return $this;
    }

    public function removeForumMembers(ForumMembers $forumMembers): self
    {
        if ($this->ForumMembers->contains($forumMembers)) {
            $this->ForumMembers->removeElement($forumMembers);
            // set the owning side to null (unless already changed)
            if ($forumMembers->getForum() === $this) {
                $forumMembers->setForum(null);
            }
        }

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|UserForumRanks[]
     */
    public function getUserForumRanks(): Collection
    {
        return $this->userForumRanks;
    }

    public function addUserForumRank(UserForumRanks $userForumRank): self
    {
        if (!$this->userForumRanks->contains($userForumRank)) {
            $this->userForumRanks[] = $userForumRank;
            $userForumRank->setForum($this);
        }

        return $this;
    }

    public function removeUserForumRank(UserForumRanks $userForumRank): self
    {
        if ($this->userForumRanks->contains($userForumRank)) {
            $this->userForumRanks->removeElement($userForumRank);
            // set the owning side to null (unless already changed)
            if ($userForumRank->getForum() === $this) {
                $userForumRank->setForum(null);
            }
        }

        return $this;
    }

    public function getThemes(): ?Themes
    {
        return $this->themes;
    }

    public function setThemes(?Themes $themes): self
    {
        $this->themes = $themes;

        // set (or unset) the owning side of the relation if necessary
        $newUnderForum = null === $themes ? null : $this;
        if ($themes->getUnderForum() !== $newUnderForum) {
            $themes->setUnderForum($newUnderForum);
        }

        return $this;
    }

}
