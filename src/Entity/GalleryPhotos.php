<?php

namespace App\Entity;

use App\Repository\GalleryPhotosRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=GalleryPhotosRepository::class)
 */
class GalleryPhotos
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=GalleryAlbum::class, inversedBy="galleryPhotos")
     */
    private $album;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploadedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $softDelete;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="galleryPhotos")
     */
    private $galleryId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalFilename;
    
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

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getAlbum(): ?GalleryAlbum
    {
        return $this->album;
    }

    public function setAlbum(?GalleryAlbum $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeInterface $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getSoftDelete(): ?bool
    {
        return $this->softDelete;
    }

    public function setSoftDelete(bool $softDelete): self
    {
        $this->softDelete = $softDelete;

        return $this;
    }

    public function getUnderGalleryId(): ?Gallery
    {
        return $this->galleryId;
    }

    public function setUnderGalleryId(?Gallery $galleryId): self
    {
        $this->galleryId = $galleryId;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getGalleryId(): ?Gallery
    {
        return $this->galleryId;
    }

    public function setGalleryId(?Gallery $galleryId): self
    {
        $this->galleryId = $galleryId;

        return $this;
    }
}
