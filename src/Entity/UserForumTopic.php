<?php

namespace App\Entity;

use App\Repository\UserForumTopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserForumTopicRepository::class)
 */
class UserForumTopic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="userForumTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumForum::class, inversedBy="forumTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forum;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $softDelete = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastPostAt;

    /**
     * @ORM\OneToMany(targetEntity=UserForumPost::class, mappedBy="ForumTopic", fetch="EXTRA_LAZY")
     */
    private $forumPosts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsItUserForumTopic;

    public function __construct()
    {
        $this->forumPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getForum(): ?ForumForum
    {
        return $this->forum;
    }

    public function setForum(?ForumForum $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    public function getSoftDelete(): ?bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(?bool $softDelete): self
    {
        $this->softDelete = $softDelete;

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

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getLastPostAt(): ?\DateTimeInterface
    {
        return $this->lastPostAt;
    }

    public function setLastPostAt(\DateTimeInterface $lastPostAt): self
    {
        $this->lastPostAt = $lastPostAt;

        return $this;
    }

    /**
     * @return Collection|ForumPost[]
     */
    public function getForumPosts(): Collection
    {
        return $this->forumPosts;
    }

    /**
     * @return Collection|ForumPost[]
     */
    public function getForumLastPost(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(['createdAt' => 'DESC'])
            ->setMaxResults('1')
        ;

        return $this->forumPosts->matching($criteria);
    }

    public function addForumPost(ForumPost $forumPost): self
    {
        if (!$this->forumPosts->contains($forumPost)) {
            $this->forumPosts[] = $forumPost;
            $forumPost->setForumTopic($this);
        }

        return $this;
    }

    public function removeForumPost(ForumPost $forumPost): self
    {
        if ($this->forumPosts->contains($forumPost)) {
            $this->forumPosts->removeElement($forumPost);
            // set the owning side to null (unless already changed)
            if ($forumPost->getForumTopic() === $this) {
                $forumPost->setForumTopic(null);
            }
        }

        return $this;
    }

    public function getIsItUserForumTopic(): ?bool
    {
        return $this->IsItUserForumTopic;
    }

    public function setIsItUserForumTopic(?bool $IsItUserForumTopic): self
    {
        $this->IsItUserForumTopic = $IsItUserForumTopic;

        return $this;
    }
}
