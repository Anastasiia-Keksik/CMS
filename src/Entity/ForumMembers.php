<?php

namespace App\Entity;

use App\Repository\ForumMembersRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ForumMembersRepository::class)
 */
class ForumMembers
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserPrivateForum::class, inversedBy="ForumMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Forum;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="forumsMember")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Member;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $role = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $pending;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $Rank;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getForum(): ?UserPrivateForum
    {
        return $this->Forum;
    }

    public function setForum(?UserPrivateForum $Forum): self
    {
        $this->Forum = $Forum;

        return $this;
    }

    public function getMember(): ?Account
    {
        return $this->Member;
    }

    public function setMember(?Account $Member): self
    {
        $this->Member = $Member;

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

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(?array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPending(): ?bool
    {
        return $this->pending;
    }

    public function setPending(bool $pending): self
    {
        $this->pending = $pending;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->Rank;
    }

    public function setRank(?int $Rank): self
    {
        $this->Rank = $Rank;

        return $this;
    }
}
