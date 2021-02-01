<?php

namespace App\Entity;

use App\Repository\EpisodeSoundsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeSoundsRepository::class)
 */
class EpisodeSounds
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="episodeSounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ComicEpisode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $startPoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endPoint;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComicEpisode(): ?ComicEpisode
    {
        return $this->ComicEpisode;
    }

    public function setComicEpisode(?ComicEpisode $ComicEpisode): self
    {
        $this->ComicEpisode = $ComicEpisode;

        return $this;
    }

    public function getStartPoint(): ?int
    {
        return $this->startPoint;
    }

    public function setStartPoint(?int $startPoint): self
    {
        $this->startPoint = $startPoint;

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

    public function getBackground(): ?bool
    {
        return $this->background;
    }

    public function setBackground(?bool $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getEndPoint(): ?int
    {
        return $this->endPoint;
    }

    public function setEndPoint(?int $endPoint): self
    {
        $this->endPoint = $endPoint;

        return $this;
    }
}
