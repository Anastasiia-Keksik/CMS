<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, inversedBy="wallet", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private $money;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private $eventMoney;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Account
    {
        return $this->User;
    }

    public function setUser(Account $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function setMoney(?float $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getEventMoney(): ?float
    {
        return $this->eventMoney;
    }

    public function setEventMoney(?float $eventMoney): self
    {
        $this->eventMoney = $eventMoney;

        return $this;
    }
}
