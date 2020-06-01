<?php

namespace App\Entity;

use App\Repository\ForumForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ForumForumRepository::class)
 */
class ForumForum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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

    public function __construct()
    {
        $this->forumTopics = new ArrayCollection();
        $this->forumPosts = new ArrayCollection();
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

}
