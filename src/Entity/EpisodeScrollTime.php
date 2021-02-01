<?php

namespace App\Entity;

use App\Repository\EpisodeScrollTimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeScrollTimeRepository::class)
 */
class EpisodeScrollTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $speed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ExpectedPosition;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $orderNbr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?ComicEpisode
    {
        return $this->Episode;
    }

    public function setEpisode(?ComicEpisode $Episode): self
    {
        $this->Episode = $Episode;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getExpectedPosition(): ?int
    {
        return $this->ExpectedPosition;
    }

    public function setExpectedPosition(?int $ExpectedPosition): self
    {
        $this->ExpectedPosition = $ExpectedPosition;

        return $this;
    }

    public function getOrderNbr(): ?int
    {
        return $this->orderNbr;
    }

    public function setOrderNbr(?int $orderNbr): self
    {
        $this->orderNbr = $orderNbr;

        return $this;
    }
}
