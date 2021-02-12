<?php

namespace App\Entity;

use App\Repository\EpisodeToAObjectMTMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeToAObjectMTMRepository::class)
 */
class EpisodeToAObjectMTM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="episodeToAObjectMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    /**
     * @ORM\ManyToOne(targetEntity=ArtObject::class, inversedBy="episodeToAObjectMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Object;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $safeDelete;

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

    public function getObject(): ?ArtObject
    {
        return $this->Object;
    }

    public function setObject(?ArtObject $Object): self
    {
        $this->Object = $Object;

        return $this;
    }

    public function getSafeDelete(): ?bool
    {
        return $this->safeDelete;
    }

    public function setSafeDelete(?bool $safeDelete): self
    {
        $this->safeDelete = $safeDelete;

        return $this;
    }
}
