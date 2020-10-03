<?php

namespace App\Entity;

use App\Repository\ProfileDesignSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProfileDesignSettingsRepository::class)
 */
class ProfileDesignSettings
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, inversedBy="profileDesignSettings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Account;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $settings = [];
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
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

    public function getSettings(): ?array
    {
        return $this->settings;
    }

    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }
}
