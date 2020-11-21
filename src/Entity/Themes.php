<?php

namespace App\Entity;

use App\Repository\ThemesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemesRepository::class)
 */
class Themes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="smallint")
     */
    private $under;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ModalBgType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ModalBg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ModalBgSound;

    /**
     * @ORM\OneToOne(targetEntity=UserPrivateForum::class, inversedBy="themes", cascade={"persist", "remove"})
     */
    private $underForum;

    public function getId(): ?int
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

    public function getUnder(): ?int
    {
        return $this->under;
    }

    public function setUnder(int $under): self
    {
        $this->under = $under;

        return $this;
    }

    public function getModalBgType(): ?int
    {
        return $this->ModalBgType;
    }

    public function setModalBgType(?int $ModalBgType): self
    {
        $this->ModalBgType = $ModalBgType;

        return $this;
    }

    public function getModalBg(): ?string
    {
        return $this->ModalBg;
    }

    public function setModalBg(?string $ModalBg): self
    {
        $this->ModalBg = $ModalBg;

        return $this;
    }

    public function getModalBgSound(): ?string
    {
        return $this->ModalBgSound;
    }

    public function setModalBgSound(?string $ModalBgSound): self
    {
        $this->ModalBgSound = $ModalBgSound;

        return $this;
    }

    public function getUnderForum(): ?UserPrivateForum
    {
        return $this->underForum;
    }

    public function setUnderForum(?UserPrivateForum $underForum): self
    {
        $this->underForum = $underForum;

        return $this;
    }
}
