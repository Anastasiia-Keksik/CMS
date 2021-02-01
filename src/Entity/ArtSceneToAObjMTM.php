<?php

namespace App\Entity;

use App\Repository\ArtSceneToAObjMTMRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtSceneToAObjMTMRepository::class)
 */
class ArtSceneToAObjMTM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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

    public function getId(): ?int
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
}
