<?php

namespace App\Entity;

use App\Repository\UserForumRanksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserForumRanksRepository::class)
 */
class UserForumRanks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserPrivateForum::class, inversedBy="userForumRanks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $finish;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $special;

    /**
     * @ORM\ManyToMany(targetEntity=Account::class, inversedBy="userForumRanks")
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ImageLong;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ImageWide;

    public function __construct()
    {
        $this->User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForum(): ?UserPrivateForum
    {
        return $this->forum;
    }

    public function setForum(?UserPrivateForum $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(?int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getFinish(): ?int
    {
        return $this->finish;
    }

    public function setFinish(?int $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getSpecial(): ?bool
    {
        return $this->special;
    }

    public function setSpecial(?bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Account $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
        }

        return $this;
    }

    public function removeUser(Account $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
        }

        return $this;
    }

    public function getImageLong(): ?string
    {
        return $this->ImageLong;
    }

    public function setImageLong(?string $ImageLong): self
    {
        $this->ImageLong = $ImageLong;

        return $this;
    }

    public function getImageWide(): ?string
    {
        return $this->ImageWide;
    }

    public function setImageWide(?string $ImageWide): self
    {
        $this->ImageWide = $ImageWide;

        return $this;
    }
}
