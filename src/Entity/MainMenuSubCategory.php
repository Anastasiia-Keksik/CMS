<?php

namespace App\Entity;

use App\Repository\MainMenuSubCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MainMenuSubCategoryRepository::class)
 */
class MainMenuSubCategory
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
     * @ORM\ManyToOne(targetEntity=MainMenuCategory::class, inversedBy="mainMenuSubCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $MainMenuCategory;

    /**
     * @ORM\OneToMany(targetEntity=MainMenuSubChild::class, mappedBy="MainMenuSubCategory")
     */
    private $mainMenuSubChildren;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden;

    public function __construct()
    {
        $this->mainMenuSubChildren = new ArrayCollection();
    }

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

    public function getMainMenuCategory(): ?MainMenuCategory
    {
        return $this->MainMenuCategory;
    }

    public function setMainMenuCategory(?MainMenuCategory $MainMenuCategory): self
    {
        $this->MainMenuCategory = $MainMenuCategory;

        return $this;
    }

    /**
     * @return Collection|MainMenuSubChild[]
     */
    public function getMainMenuSubChildren(): Collection
    {
        return $this->mainMenuSubChildren;
    }

    public function addMainMenuSubChild(MainMenuSubChild $mainMenuSubChild): self
    {
        if (!$this->mainMenuSubChildren->contains($mainMenuSubChild)) {
            $this->mainMenuSubChildren[] = $mainMenuSubChild;
            $mainMenuSubChild->setMainMenuSubCategory($this);
        }

        return $this;
    }

    public function removeMainMenuSubChild(MainMenuSubChild $mainMenuSubChild): self
    {
        if ($this->mainMenuSubChildren->contains($mainMenuSubChild)) {
            $this->mainMenuSubChildren->removeElement($mainMenuSubChild);
            // set the owning side to null (unless already changed)
            if ($mainMenuSubChild->getMainMenuSubCategory() === $this) {
                $mainMenuSubChild->setMainMenuSubCategory(null);
            }
        }

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
