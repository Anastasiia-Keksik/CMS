<?php

namespace App\Entity;

use App\Repository\ComicEpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ComicEpisodeRepository::class)
 */
class ComicEpisode
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

//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $sound;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $likes = 0;

    /**
     * @ORM\Column(type="float", nullable=false, options={"default":0})
     */
    private $price = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Comic::class, inversedBy="comicEpisodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comic;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $Views = 0;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $published = 0;

    /**
     * @ORM\OneToMany(targetEntity=Favourites::class, mappedBy="ComicEpisodes")
     */
    private $favourites;

    /**
     * @ORM\OneToOne(targetEntity=Project::class, mappedBy="ComicEpisode", cascade={"persist", "remove"})
     */
    private $project;

    private $isMine = false;

    private $revenue;

    private $income;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeSounds::class, mappedBy="ComicEpisode")
     */
    private $episodeSounds;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeScrollTime::class, mappedBy="Episode")
     */
    private $yes;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeImage::class, mappedBy="Episode")
     */
    private $episodeImages;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeToArtSceneMTM::class, mappedBy="Episode", orphanRemoval=true)
     */
    private $episodeToArtSceneMTMs;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeToAObjectMTM::class, mappedBy="Episode")
     */
    private $episodeToAObjectMTMs;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->favourites = new ArrayCollection();
        $this->episodeSounds = new ArrayCollection();
        $this->yes = new ArrayCollection();
        $this->episodeImages = new ArrayCollection();
        $this->episodeToArtSceneMTMs = new ArrayCollection();
        $this->episodeToAObjectMTMs = new ArrayCollection();
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

//    public function getSound(): ?bool
//    {
//        return $this->sound;
//    }
//
//    public function setSound(bool $sound): self
//    {
//        $this->sound = $sound;
//
//        return $this;
//    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getComic(): ?Comic
    {
        return $this->comic;
    }

    public function setComic(?Comic $comic): self
    {
        $this->comic = $comic;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->Views;
    }

    public function setViews(int $Views): self
    {
        $this->Views = $Views;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getIsMine(): ?bool
    {
        return $this->isMine;
    }

    public function setIsMine(bool $isMine): self
    {
        $this->isMine = $isMine;

        return $this;
    }

    public function getRevenue(): ?int
    {
        return $this->revenue;
    }

    public function setRevenue(int $revenue): self
    {
        $this->revenue = $revenue;

        return $this;
    }

    public function getIncome(): ?int
    {
        return $this->income;
    }

    public function setIncome(int $income): self
    {
        $this->income = $income;

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
            $favourite->setComicEpisodes($this);
        }

        return $this;
    }

    public function removeFavourite(Favourites $favourite): self
    {
        if ($this->favourites->contains($favourite)) {
            $this->favourites->removeElement($favourite);
            // set the owning side to null (unless already changed)
            if ($favourite->getComicEpisodes() === $this) {
                $favourite->setComicEpisodes(null);
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        // unset the owning side of the relation if necessary
        if ($project === null && $this->project !== null) {
            $this->project->setComicEpisode(null);
        }

        // set the owning side of the relation if necessary
        if ($project !== null && $project->getComicEpisode() !== $this) {
            $project->setComicEpisode($this);
        }

        $this->project = $project;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection|EpisodeSounds[]
     */
    public function getEpisodeSounds(): Collection
    {
        return $this->episodeSounds;
    }

    public function addEpisodeSound(EpisodeSounds $episodeSound): self
    {
        if (!$this->episodeSounds->contains($episodeSound)) {
            $this->episodeSounds[] = $episodeSound;
            $episodeSound->setComicEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeSound(EpisodeSounds $episodeSound): self
    {
        if ($this->episodeSounds->removeElement($episodeSound)) {
            // set the owning side to null (unless already changed)
            if ($episodeSound->getComicEpisode() === $this) {
                $episodeSound->setComicEpisode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EpisodeScrollTime[]
     */
    public function getScrollTimes(): Collection
    {
        return $this->yes;
    }

    public function addScrollTime(EpisodeScrollTime $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setEpisode($this);
        }

        return $this;
    }

    public function removeScrollTime(EpisodeScrollTime $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getEpisode() === $this) {
                $ye->setEpisode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EpisodeImage[]
     */
    public function getEpisodeImages(): Collection
    {
        return $this->episodeImages;
    }

    public function addEpisodeImage(EpisodeImage $episodeImage): self
    {
        if (!$this->episodeImages->contains($episodeImage)) {
            $this->episodeImages[] = $episodeImage;
            $episodeImage->setEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeImage(EpisodeImage $episodeImage): self
    {
        if ($this->episodeImages->removeElement($episodeImage)) {
            // set the owning side to null (unless already changed)
            if ($episodeImage->getEpisode() === $this) {
                $episodeImage->setEpisode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EpisodeToArtSceneMTM[]
     */
    public function getEpisodeToArtSceneMTMs(): Collection
    {
        return $this->episodeToArtSceneMTMs;
    }

    public function addEpisodeToArtSceneMTM(EpisodeToArtSceneMTM $episodeToArtSceneMTM): self
    {
        if (!$this->episodeToArtSceneMTMs->contains($episodeToArtSceneMTM)) {
            $this->episodeToArtSceneMTMs[] = $episodeToArtSceneMTM;
            $episodeToArtSceneMTM->setEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeToArtSceneMTM(EpisodeToArtSceneMTM $episodeToArtSceneMTM): self
    {
        if ($this->episodeToArtSceneMTMs->removeElement($episodeToArtSceneMTM)) {
            // set the owning side to null (unless already changed)
            if ($episodeToArtSceneMTM->getEpisode() === $this) {
                $episodeToArtSceneMTM->setEpisode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EpisodeToAObjectMTM[]
     */
    public function getEpisodeToAObjectMTMs(): Collection
    {
        return $this->episodeToAObjectMTMs;
    }

    public function addEpisodeToAObjectMTM(EpisodeToAObjectMTM $episodeToAObjectMTM): self
    {
        if (!$this->episodeToAObjectMTMs->contains($episodeToAObjectMTM)) {
            $this->episodeToAObjectMTMs[] = $episodeToAObjectMTM;
            $episodeToAObjectMTM->setEpisode($this);
        }

        return $this;
    }

    public function removeEpisodeToAObjectMTM(EpisodeToAObjectMTM $episodeToAObjectMTM): self
    {
        if ($this->episodeToAObjectMTMs->removeElement($episodeToAObjectMTM)) {
            // set the owning side to null (unless already changed)
            if ($episodeToAObjectMTM->getEpisode() === $this) {
                $episodeToAObjectMTM->setEpisode(null);
            }
        }

        return $this;
    }
}
