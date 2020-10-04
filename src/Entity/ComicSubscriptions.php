<?php

namespace App\Entity;

use App\Repository\ComicSubscriptionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ComicSubscriptionsRepository::class)
 */
class ComicSubscriptions
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Comic::class, inversedBy="comicSubscription")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comicId;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="comicSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $uctivatedUntil;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getComicId(): ?Comic
    {
        return $this->comicId;
    }

    public function setComicId(?Comic $comicId): self
    {
        $this->comicId = $comicId;

        return $this;
    }

    public function getUserId(): ?Account
    {
        return $this->UserId;
    }

    public function setUserId(?Account $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUctivatedUntil(): ?\DateTimeInterface
    {
        return $this->uctivatedUntil;
    }

    public function setUctivatedUntil(?\DateTimeInterface $uctivatedUntil): self
    {
        $this->uctivatedUntil = $uctivatedUntil;

        return $this;
    }
}
