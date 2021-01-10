<?php

namespace App\Entity;

use App\Repository\ProjectUserConnectionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectUserConnectionRepository::class)
 */
class ProjectUserConnection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="Account")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Project;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $Revenue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $Income = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?Account
    {
        return $this->User;
    }

    public function setUser(?Account $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getRevenue(): ?int
    {
        return $this->Revenue;
    }

    public function setRevenue(?int $Revenue): self
    {
        $this->Revenue = $Revenue;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getIncome(): ?int
    {
        return $this->Income;
    }

    public function setIncome(?int $Income): self
    {
        $this->Income = $Income;

        return $this;
    }
}
