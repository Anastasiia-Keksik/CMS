<?php

namespace App\Entity;

use App\Repository\UserPrintScreensRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserPrintScreensRepository::class)
 */
class UserPrintScreens
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nrOfPrints;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastPrintAt;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $IPs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->user;
    }

    public function setUser(?Account $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNrOfPrints(): ?int
    {
        return $this->nrOfPrints;
    }

    public function setNrOfPrints(int $nrOfPrints): self
    {
        $this->nrOfPrints = $nrOfPrints;

        return $this;
    }

    public function getLastPrintAt(): ?\DateTimeInterface
    {
        return $this->lastPrintAt;
    }

    public function setLastPrintAt(\DateTimeInterface $lastPrintAt): self
    {
        $this->lastPrintAt = $lastPrintAt;

        return $this;
    }

    public function getIPs(): ?string
    {
        return $this->IPs;
    }

    public function setIPs(?string $IPs): self
    {
        $this->IPs = $IPs;

        return $this;
    }
}
