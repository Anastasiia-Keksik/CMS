<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 */
class Gallery
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
     * @ORM\OneToOne(targetEntity=Account::class, inversedBy="gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="integer")
     */
    private $albums;

    /**
     * @ORM\Column(type="integer")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=GalleryAlbum::class, mappedBy="gallery", orphanRemoval=true)
     */
    private $galleryAlbums;

    public function __construct()
    {
        $this->galleryAlbums = new ArrayCollection();
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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getAlbums(): ?int
    {
        return $this->albums;
    }

    public function setAlbums(int $albums): self
    {
        $this->albums = $albums;

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
     * @return Collection|GalleryAlbum[]
     */
    public function getGalleryAlbums(): Collection
    {
        return $this->galleryAlbums;
    }

    public function addGalleryAlbum(GalleryAlbum $galleryAlbum): self
    {
        if (!$this->galleryAlbums->contains($galleryAlbum)) {
            $this->galleryAlbums[] = $galleryAlbum;
            $galleryAlbum->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryAlbum(GalleryAlbum $galleryAlbum): self
    {
        if ($this->galleryAlbums->contains($galleryAlbum)) {
            $this->galleryAlbums->removeElement($galleryAlbum);
            // set the owning side to null (unless already changed)
            if ($galleryAlbum->getGallery() === $this) {
                $galleryAlbum->setGallery(null);
            }
        }

        return $this;
    }
}
