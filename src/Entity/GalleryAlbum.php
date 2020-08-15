<?php

namespace App\Entity;

use App\Repository\GalleryAlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalleryAlbumRepository::class)
 */
class GalleryAlbum
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifiedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="galleryAlbums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gallery;

    /**
     * @ORM\Column(type="integer")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=GalleryPhotos::class, mappedBy="album")
     */
    private $galleryPhotos;

    /**
     * @ORM\OneToOne(targetEntity=GalleryPhotos::class, cascade={"persist", "remove"})
     */
    private $cover;

    public function __construct()
    {
        $this->galleryPhotos = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getPhotos(): ?int
    {
        return $this->photos;
    }

    public function setPhotos(int $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return Collection|GalleryPhotos[]
     */
    public function getGalleryPhotos(): Collection
    {
        return $this->galleryPhotos;
    }

    public function addGalleryPhoto(GalleryPhotos $galleryPhoto): self
    {
        if (!$this->galleryPhotos->contains($galleryPhoto)) {
            $this->galleryPhotos[] = $galleryPhoto;
            $galleryPhoto->setAlbum($this);
        }

        return $this;
    }

    public function removeGalleryPhoto(GalleryPhotos $galleryPhoto): self
    {
        if ($this->galleryPhotos->contains($galleryPhoto)) {
            $this->galleryPhotos->removeElement($galleryPhoto);
            // set the owning side to null (unless already changed)
            if ($galleryPhoto->getAlbum() === $this) {
                $galleryPhoto->setAlbum(null);
            }
        }

        return $this;
    }

    public function getCover(): ?GalleryPhotos
    {
        return $this->cover;
    }

    public function setCover(?GalleryPhotos $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
