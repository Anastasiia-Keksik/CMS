<?php

namespace App\Entity;

use App\Repository\EpisodeToArtSceneMTMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeToArtSceneMTMRepository::class)
 */
class EpisodeToArtSceneMTM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ArtScene::class, inversedBy="episodeToArtSceneMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ArtScene;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="episodeToArtSceneMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtScene(): ?ArtScene
    {
        return $this->ArtScene;
    }

    public function setArtScene(?ArtScene $ArtScene): self
    {
        $this->ArtScene = $ArtScene;

        return $this;
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
}
