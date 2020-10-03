<?php

namespace App\Entity;

use App\Repository\IpLastLogonLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=IpLastLogonLogRepository::class)
 */
class IpLastLogonLog
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="IP")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $IP;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LogoutAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sessionId;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): self
    {
        $this->IP = $IP;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLogoutAt(): ?\DateTimeInterface
    {
        return $this->LogoutAt;
    }

    public function setLogoutAt(?\DateTimeInterface $LogoutAt): self
    {
        $this->LogoutAt = $LogoutAt;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }
}
