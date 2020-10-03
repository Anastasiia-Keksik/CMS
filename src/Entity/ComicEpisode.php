<?php

namespace App\Entity;

use App\Repository\ComicEpisodeRepository;
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
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Comic::class, inversedBy="comicEpisodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comic;

    /**
     * @ORM\Column(type="integer")
     */
    private $Views;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    public function __construct()
    {
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
}