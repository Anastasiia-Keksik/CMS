<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account_source;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="contacts_backward")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account_Targets;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_At;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAccountSource(): ?Account
    {
        return $this->Account_source;
    }

    public function setAccountSource(?Account $Account_source): self
    {
        $this->Account_source = $Account_source;

        return $this;
    }

    public function getAccountTargets(): ?Account
    {
        return $this->Account_Targets;
    }

    public function setAccountTargets(?Account $Account_Targets): self
    {
        $this->Account_Targets = $Account_Targets;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_At;
    }

    public function setCreatedAt(\DateTimeInterface $created_At): self
    {
        $this->created_At = $created_At;

        return $this;
    }
}
