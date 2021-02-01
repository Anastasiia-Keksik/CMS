<?php

namespace App\Entity;

use App\Repository\UserToAObjMTMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserToAObjMTMRepository::class)
 */
class UserToAObjMTM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="userToAObjMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=ArtObject::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Obj;

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

    public function getObj(): ?ArtObject
    {
        return $this->Obj;
    }

    public function setObj(?ArtObject $Obj): self
    {
        $this->Obj = $Obj;

        return $this;
    }
}
