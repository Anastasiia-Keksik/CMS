<?php

namespace App\Entity;

use App\Repository\ArtSceneToUserMTMRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ArtSceneToUserMTMRepository::class)
 */
class ArtSceneToUserMTM
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
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

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

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

    public function getArtScene(): ?ArtScene
    {
        return $this->ArtScene;
    }

    public function setArtScene(?ArtScene $ArtScene): self
    {
        $this->ArtScene = $ArtScene;

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
}
