<?php

namespace App\Entity;

use App\Repository\ArtObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ArtObjectRepository::class)
 */
class ArtObject
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
    private $fileName;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=EpisodeToAObjectMTM::class, mappedBy="Object")
     */
    private $episodeToAObjectMTMs;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->episodeToAObjectMTMs = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|EpisodeToAObjectMTM[]
     */
    public function getEpisodeToAObjectMTMs(): Collection
    {
        return $this->episodeToAObjectMTMs;
    }

    public function addEpisodeToAObjectMTM(EpisodeToAObjectMTM $episodeToAObjectMTM): self
    {
        if (!$this->episodeToAObjectMTMs->contains($episodeToAObjectMTM)) {
            $this->episodeToAObjectMTMs[] = $episodeToAObjectMTM;
            $episodeToAObjectMTM->setObject($this);
        }

        return $this;
    }

    public function removeEpisodeToAObjectMTM(EpisodeToAObjectMTM $episodeToAObjectMTM): self
    {
        if ($this->episodeToAObjectMTMs->removeElement($episodeToAObjectMTM)) {
            // set the owning side to null (unless already changed)
            if ($episodeToAObjectMTM->getObject() === $this) {
                $episodeToAObjectMTM->setObject(null);
            }
        }

        return $this;
    }
}
