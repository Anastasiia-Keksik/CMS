<?php

namespace App\Entity;

use App\Repository\UserForumTopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserForumTopicRepository::class)
 */
class UserForumTopic
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastPostAt;

    /**
     * @ORM\OneToMany(targetEntity=UserForumPost::class, mappedBy="ForumTopic", fetch="EXTRA_LAZY")
     */
    private $forumPosts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sticky;

    /**
     * @ORM\Column(type="integer")
     */
    private $replies;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\OneToMany(targetEntity=Favourites::class, mappedBy="UserForumTopic")
     */
    private $favourites;

    public function __construct()
    {
        $this->forumPosts = new ArrayCollection();
        $this->id = Uuid::uuid4();
        $this->favourites = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getForum(): ?UserForumForum
    {
        return $this->forum;
    }

    public function setForum(?UserForumForum $forum): self
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

    public function getSticky(): ?bool
    {
        return $this->sticky;
    }

    public function setSticky(bool $sticky): self
    {
        $this->sticky = $sticky;

        return $this;
    }

    public function getReplies(): ?int
    {
        return $this->replies;
    }

    public function setReplies(int $replies): self
    {
        $this->replies = $replies;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return Collection|Favourites[]
     */
    public function getFavourites(): Collection
    {
        return $this->favourites;
    }

    public function addFavourite(Favourites $favourite): self
    {
        if (!$this->favourites->contains($favourite)) {
            $this->favourites[] = $favourite;
            $favourite->setForumTopic($this);
        }

        return $this;
    }

    public function removeFavourite(Favourites $favourite): self
    {
        if ($this->favourites->contains($favourite)) {
            $this->favourites->removeElement($favourite);
            // set the owning side to null (unless already changed)
            if ($favourite->getForumTopic() === $this) {
                $favourite->setForumTopic(null);
            }
        }

        return $this;
    }
}
