<?php

namespace App\Entity;

use App\Repository\ArtSceneToUserMTMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtSceneToUserMTMRepository::class)
 */
class ArtSceneToUserMTM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="artSceneToUserMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=ArtScene::class, inversedBy="artSceneToUserMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ArtScene;

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

    public function getArtScene(): ?ArtScene
    {
        return $this->ArtScene;
    }

    public function setArtScene(?ArtScene $ArtScene): self
    {
        $this->ArtScene = $ArtScene;

        return $this;
    }
}
