<?php

namespace App\Entity;

use App\Repository\AccountSignatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AccountSignatureRepository::class)
 */
class AccountSignature
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString()
    {
        return (string) $this->Signature;
    }

    public function getId(): ?string
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
