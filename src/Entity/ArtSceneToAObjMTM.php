<?php

namespace App\Entity;

use App\Repository\ArtSceneToAObjMTMRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ArtSceneToAObjMTMRepository::class)
 */
class ArtSceneToAObjMTM
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ArtObject::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Obj;

    /**
     * @ORM\ManyToOne(targetEntity=ArtScene::class, inversedBy="artSceneToAObjMTMs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ArtScene;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $posx;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $posy;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $rotation;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $opacity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $speed;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getArtScene(): ?ArtScene
    {
        return $this->ArtScene;
    }

    public function setArtScene(?ArtScene $ArtScene): self
    {
        $this->ArtScene = $ArtScene;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getPosx(): ?int
    {
        return $this->posx;
    }

    public function setPosx(?int $posx): self
    {
        $this->posx = $posx;

        return $this;
    }

    public function getPosy(): ?int
    {
        return $this->posy;
    }

    public function setPosy(?int $posy): self
    {
        $this->posy = $posy;

        return $this;
    }

    public function getRotation(): ?int
    {
        return $this->rotation;
    }

    public function setRotation(?int $rotation): self
    {
        $this->rotation = $rotation;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getOpacity(): ?float
    {
        return $this->opacity;
    }

    public function setOpacity(?float $opacity): self
    {
        $this->opacity = $opacity;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(?float $speed): self
    {
        $this->speed = $speed;

        return $this;
    }
}
