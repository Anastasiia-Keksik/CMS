<?php

namespace App\Entity;

use App\Repository\ComicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ComicRepository::class)
 */
class Comic
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=ComicCategories::class, inversedBy="comics")
     */
    private $category1;

    /**
     * @ORM\ManyToOne(targetEntity=ComicCategories::class)
     */
    private $category2;

    /**
     * @ORM\ManyToOne(targetEntity=ComicCategories::class)
     */
    private $category3;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ViewerStyle;

    /**
     * @ORM\OneToMany(targetEntity=ComicSubscriptions::class, mappedBy="comicId", orphanRemoval=true)
     */
    private $comicSubscription;

    /**
     * @ORM\OneToMany(targetEntity=ComicEpisode::class, mappedBy="comic", orphanRemoval=true)
     */
    private $comicEpisodes;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="comics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $brutality;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nudity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=Project::class, mappedBy="Comic", cascade={"persist", "remove"})
     */
    private $project;

    public function __construct()
    {
        $this->comicSubscription = new ArrayCollection();
        $this->comicEpisodes = new ArrayCollection();

        $this->id = Uuid::uuid4();

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory1(): ?ComicCategories
    {
        return $this->category1;
    }

    public function setCategory1(?ComicCategories $category1): self
    {
        $this->category1 = $category1;

        return $this;
    }

    public function getCategory2(): ?ComicCategories
    {
        return $this->category2;
    }

    public function setCategory2(?ComicCategories $category2): self
    {
        $this->category2 = $category2;

        return $this;
    }

    public function getCategory3(): ?ComicCategories
    {
        return $this->category3;
    }

    public function setCategory3(?ComicCategories $category3): self
    {
        $this->category3 = $category3;

        return $this;
    }

    public function getViewerStyle(): ?int
    {
        return $this->ViewerStyle;
    }

    public function setViewerStyle(int $ViewerStyle): self
    {
        $this->ViewerStyle = $ViewerStyle;

        return $this;
    }

    /**
     * @return Collection|ComicSubscriptions[]
     */
    public function getComicSubscription(): Collection
    {
        return $this->comicSubscription;
    }

    public function addComicSubscription(ComicSubscriptions $comicSubscription): self
    {
        if (!$this->comicSubscription->contains($comicSubscription)) {
            $this->comicSubscription[] = $comicSubscription;
            $comicSubscription->setComicId($this);
        }

        return $this;
    }

    public function removeComicSubscription(ComicSubscriptions $comicSubscription): self
    {
        if ($this->comicSubscription->contains($comicSubscription)) {
            $this->comicSubscription->removeElement($comicSubscription);
            // set the owning side to null (unless already changed)
            if ($comicSubscription->getComicId() === $this) {
                $comicSubscription->setComicId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ComicEpisode[]
     */
    public function getComicEpisodes(): Collection
    {
        return $this->comicEpisodes;
    }

    public function addComicEpisode(ComicEpisode $comicEpisode): self
    {
        if (!$this->comicEpisodes->contains($comicEpisode)) {
            $this->comicEpisodes[] = $comicEpisode;
            $comicEpisode->setComic($this);
        }

        return $this;
    }

    public function removeComicEpisode(ComicEpisode $comicEpisode): self
    {
        if ($this->comicEpisodes->contains($comicEpisode)) {
            $this->comicEpisodes->removeElement($comicEpisode);
            // set the owning side to null (unless already changed)
            if ($comicEpisode->getComic() === $this) {
                $comicEpisode->setComic(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Account
    {
        return $this->Author;
    }

    public function setAuthor(?Account $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getBrutality(): ?bool
    {
        return $this->brutality;
    }

    public function setBrutality(?bool $brutality): self
    {
        $this->brutality = $brutality;

        return $this;
    }

    public function getNudity(): ?bool
    {
        return $this->nudity;
    }

    public function setNudity(?bool $nudity): self
    {
        $this->nudity = $nudity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }
}
