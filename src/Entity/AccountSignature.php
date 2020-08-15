<?php

namespace App\Entity;

use App\Repository\AccountSignatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountSignatureRepository::class)
 */
class AccountSignature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, inversedBy="Signature", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Signature;

    public function __toString()
    {
        return (string) $this->Signature;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->Account;
    }

    public function setAccount(Account $Account): self
    {
        $this->Account = $Account;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->Signature;
    }

    public function setSignature(?string $Signature): self
    {
        $this->Signature = $Signature;

        return $this;
    }
}
