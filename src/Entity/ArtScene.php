<?php

namespace App\Entity;

use App\Repository\ArtSceneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ArtSceneRepository::class)
 */
class ArtScene
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $posy;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $posx;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $rotation;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeToArtSceneMTM::class, mappedBy="ArtScene", orphanRemoval=true)
     */
    private $episodeToArtSceneMTMs;

    /**
     * @ORM\OneToMany(targetEntity=ArtSceneToAObjMTM::class, mappedBy="ArtScene", orphanRemoval=true)
     */
    private $artSceneToAObjMTMs;

    /**
     * @ORM\OneToMany(targetEntity=ArtSceneToUserMTM::class, mappedBy="ArtScene")
     */
    private $artSceneToUserMTMs;

    public function __construct()
    {
        $this->episodeToArtSceneMTMs = new ArrayCollection();
        $this->artSceneToAObjMTMs = new ArrayCollection();
        $this->artSceneToUserMTMs = new ArrayCollection();
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

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

    public function getPosy(): ?int
    {
        return $this->posy;
    }

    public function setPosy(?int $posy): self
    {
        $this->posy = $posy;

        return $this;
    }

    public function getPosx(): ?int
    {
        return $this->posx;
    }

    public function setPosx(?int $posx): self
    {
        $this->posx = $posx;

        return $this;
    }

    public function getRotation(): ?int
    {
        return $this->rotation;
    }

    public function setRotation(?int $rotation): self
    {
        $this->rotation = $rotation;

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
            $episodeToArtSceneMTM->setArtScene($this);
        }

        return $this;
    }

    public function removeEpisodeToArtSceneMTM(EpisodeToArtSceneMTM $episodeToArtSceneMTM): self
    {
        if ($this->episodeToArtSceneMTMs->removeElement($episodeToArtSceneMTM)) {
            // set the owning side to null (unless already changed)
            if ($episodeToArtSceneMTM->getArtScene() === $this) {
                $episodeToArtSceneMTM->setArtScene(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArtSceneToAObjMTM[]
     */
    public function getArtSceneToAObjMTMs(): Collection
    {
        return $this->artSceneToAObjMTMs;
    }

    public function addArtSceneToAObjMTM(ArtSceneToAObjMTM $artSceneToAObjMTM): self
    {
        if (!$this->artSceneToAObjMTMs->contains($artSceneToAObjMTM)) {
            $this->artSceneToAObjMTMs[] = $artSceneToAObjMTM;
            $artSceneToAObjMTM->setArtScene($this);
        }

        return $this;
    }

    public function removeArtSceneToAObjMTM(ArtSceneToAObjMTM $artSceneToAObjMTM): self
    {
        if ($this->artSceneToAObjMTMs->removeElement($artSceneToAObjMTM)) {
            // set the owning side to null (unless already changed)
            if ($artSceneToAObjMTM->getArtScene() === $this) {
                $artSceneToAObjMTM->setArtScene(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArtSceneToUserMTM[]
     */
    public function getArtSceneToUserMTMs(): Collection
    {
        return $this->artSceneToUserMTMs;
    }

    public function addArtSceneToUserMTM(ArtSceneToUserMTM $artSceneToUserMTM): self
    {
        if (!$this->artSceneToUserMTMs->contains($artSceneToUserMTM)) {
            $this->artSceneToUserMTMs[] = $artSceneToUserMTM;
            $artSceneToUserMTM->setArtScene($this);
        }

        return $this;
    }

    public function removeArtSceneToUserMTM(ArtSceneToUserMTM $artSceneToUserMTM): self
    {
        if ($this->artSceneToUserMTMs->removeElement($artSceneToUserMTM)) {
            // set the owning side to null (unless already changed)
            if ($artSceneToUserMTM->getArtScene() === $this) {
                $artSceneToUserMTM->setArtScene(null);
            }
        }

        return $this;
    }
}
