<?php

namespace App\Entity;

use App\Repository\EpisodeImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeImageRepository::class)
 */
class EpisodeImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="episodeImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ShowHide;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FileName;

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
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $paralaxa;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $ImageOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?ComicEpisode
    {
        return $this->Episode;
    }

    public function setEpisode(?ComicEpisode $Episode): self
    {
        $this->Episode = $Episode;

        return $this;
    }

    public function getShowHide(): ?bool
    {
        return $this->ShowHide;
    }

    public function setShowHide(bool $ShowHide): self
    {
        $this->ShowHide = $ShowHide;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->FileName;
    }

    public function setFileName(string $FileName): self
    {
        $this->FileName = $FileName;

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

    public function getParalaxa(): ?int
    {
        return $this->paralaxa;
    }

    public function setParalaxa(?int $paralaxa): self
    {
        $this->paralaxa = $paralaxa;

        return $this;
    }

    public function getImageOrder(): ?int
    {
        return $this->ImageOrder;
    }

    public function setImageOrder(?int $ImageOrder): self
    {
        $this->ImageOrder = $ImageOrder;

        return $this;
    }
}
