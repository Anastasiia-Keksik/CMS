<?php

namespace App\Entity;

use App\Repository\ForumForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ForumForumRepository::class)
 */
class ForumForum
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Posts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Topics;

    /**
     * @ORM\ManyToOne(targetEntity=ForumCategory::class, inversedBy="forumForum")
     */
    private $Category;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=ForumTopic::class, mappedBy="forum")
     * @ORM\OrderBy({"lastPostAt" = "DESC"})
     */
    private $forumTopics;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden = false;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $Icon;

    public function __construct()
    {
        $this->forumTopics = new ArrayCollection();
        $this->forumPosts = new ArrayCollection();

        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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

    public function getPosts(): ?int
    {
        return $this->Posts;
    }

    public function setPosts(?int $Posts): self
    {
        $this->Posts = $Posts;

        return $this;
    }

    public function getTopics(): ?int
    {
        return $this->Topics;
    }

    public function setTopics(?int $Topics): self
    {
        $this->Topics = $Topics;

        return $this;
    }

    public function getCategory(): ?ForumCategory
    {
        return $this->Category;
    }

    public function setCategory(?ForumCategory $Category): self
    {
        $this->Category = $Category;

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

    /**
     * @return Collection|ForumTopic[]
     */
    public function getForumTopics(): Collection
    {
        return $this->forumTopics;
    }

    /**
     * @return Collection|ForumTopic[]
     */
    public function getForumLastTopic(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(['lastPostAt' => 'DESC'])
            ->setMaxResults('1')
        ;

        return $this->forumTopics->matching($criteria);
    }

    public function addForumTopic(ForumTopic $forumTopic): self
    {
        if (!$this->forumTopics->contains($forumTopic)) {
            $this->forumTopics[] = $forumTopic;
            $forumTopic->setForum($this);
        }

        return $this;
    }

    public function removeForumTopic(ForumTopic $forumTopic): self
    {
        if ($this->forumTopics->contains($forumTopic)) {
            $this->forumTopics->removeElement($forumTopic);
            // set the owning side to null (unless already changed)
            if ($forumTopic->getForum() === $this) {
                $forumTopic->setForum(null);
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

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getIcon()
    {
        return $this->Icon;
    }

    public function setIcon($Icon): self
    {
        $this->Icon = $Icon;

        return $this;
    }

}
