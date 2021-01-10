<?php

namespace App\Entity;

use App\Repository\InvitationRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=InvitationRequestRepository::class)
 */
class InvitationRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"notification"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="invitationRequests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"notification"})
     */
    private $WhoInvite;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="invitationRequiestsReceived")
     * @ORM\JoinColumn(nullable=false)
     */
    private $WhoInvited;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"notification"})
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Circles::class)
     */
    private $circle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhoInvite(): ?Account
    {
        return $this->WhoInvite;
    }

    public function setWhoInvite(?Account $WhoInvite): self
    {
        $this->WhoInvite = $WhoInvite;

        return $this;
    }

    public function getWhoInvited(): ?Account
    {
        return $this->WhoInvited;
    }

    public function setWhoInvited(?Account $WhoInvited): self
    {
        $this->WhoInvited = $WhoInvited;

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

    public function getCircle(): ?Circles
    {
        return $this->circle;
    }

    public function setCircle(?Circles $circle): self
    {
        $this->circle = $circle;

        return $this;
    }
}
