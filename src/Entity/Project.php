<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $maxEpisodes;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Comic::class, cascade={"persist", "remove"})
     */
    private $Comic;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=ProjectUserConnection::class, mappedBy="Project", orphanRemoval=true)
     */
    private $Account;

    /**
     * @ORM\OneToOne(targetEntity=ComicEpisode::class, inversedBy="project", cascade={"persist", "remove"})
     */
    private $ComicEpisode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $CoordinationNote;

    /**
     * @ORM\OneToMany(targetEntity=LastRead::class, mappedBy="Project")
     */
    private $lastReads;

    public function __construct()
    {
        $this->Account = new ArrayCollection();
        $this->lastReads = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxEpisodes(): ?int
    {
        return $this->maxEpisodes;
    }

    public function setMaxEpisodes(?int $maxEpisodes): self
    {
        $this->maxEpisodes = $maxEpisodes;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getComic(): ?Comic
    {
        return $this->Comic;
    }

    public function setComic(?Comic $Comic): self
    {
        $this->Comic = $Comic;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|ProjectUserConnection[]
     */
    public function getAccount(): Collection
    {
        return $this->Account;
    }

    public function addAccount(ProjectUserConnection $account): self
    {
        if (!$this->Account->contains($account)) {
            $this->Account[] = $account;
            $account->setProject($this);
        }

        return $this;
    }

    public function removeAccount(ProjectUserConnection $account): self
    {
        if ($this->Account->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getProject() === $this) {
                $account->setProject(null);
            }
        }

        return $this;
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

    public function getCoordinationNote(): ?string
    {
        return $this->CoordinationNote;
    }

    public function setCoordinationNote(?string $CoordinationNote): self
    {
        $this->CoordinationNote = $CoordinationNote;

        return $this;
    }

    /**
     * @return Collection|LastRead[]
     */
    public function getLastReads(): Collection
    {
        return $this->lastReads;
    }

    public function addLastRead(LastRead $lastRead): self
    {
        if (!$this->lastReads->contains($lastRead)) {
            $this->lastReads[] = $lastRead;
            $lastRead->setProject($this);
        }

        return $this;
    }

    public function removeLastRead(LastRead $lastRead): self
    {
        if ($this->lastReads->removeElement($lastRead)) {
            // set the owning side to null (unless already changed)
            if ($lastRead->getProject() === $this) {
                $lastRead->setProject(null);
            }
        }

        return $this;
    }
}
