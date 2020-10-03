<?php

namespace App\Entity;

use App\Repository\MainMenuSubChildRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=MainMenuSubChildRepository::class)
 */
class MainMenuSubChild
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
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
     * @ORM\ManyToOne(targetEntity=MainMenuSubCategory::class, inversedBy="mainMenuSubChildren")
     * @ORM\JoinColumn(nullable=false)
     */
    private $MainMenuSubCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlValue;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
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
        $this->OrderNumber = $orderNumber;

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

    public function getMainMenuSubCategory(): ?MainMenuSubCategory
    {
        return $this->MainMenuSubCategory;
    }

    public function setMainMenuSubCategory(?MainMenuSubCategory $MainMenuSubCategory): self
    {
        $this->MainMenuSubCategory = $MainMenuSubCategory;

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

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }
}
