<?php

namespace App\Entity;

use App\Repository\LastReadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LastReadRepository::class)
 */
class LastRead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="lastReads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="lastReads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Project;

    /**
     * @ORM\Column(type="datetime")
     */
    private $readAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->User;
    }

    public function setUser(?Account $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->Project;
    }

    public function setProject(?Project $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

    public function getReadAt(): ?\DateTimeInterface
    {
        return $this->readAt;
    }

    public function setReadAt(\DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }
}
