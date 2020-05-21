<?php

namespace App\Entity;

use App\Repository\MainMenuChildRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MainMenuChildRepository::class)
 */
class MainMenuChild
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    /**
     * @ORM\ManyToOne(targetEntity=MainMenuCategory::class, inversedBy="mainMenuChildren")
     * @ORM\JoinColumn(nullable=false)
     */
    private $MainMenuCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlValue;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getMainMenuCategory(): ?MainMenuCategory
    {
        return $this->MainMenuCategory;
    }

    public function setMainMenuCategory(?MainMenuCategory $MainMenuCategory): self
    {
        $this->MainMenuCategory = $MainMenuCategory;

        return $this;
    }

    public function getUrlValue(): ?string
    {
        return $this->urlValue;
    }

    public function setUrlValue(?string $urlValue): self
    {
        $this->urlValue = $urlValue;

        return $this;
    }
}
