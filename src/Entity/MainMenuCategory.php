<?php

namespace App\Entity;

use App\Repository\MainMenuCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=MainMenuCategoryRepository::class)
 */
class MainMenuCategory
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
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="mainMenuCategories")
     */
    private $User;

    /**
     * @ORM\OneToMany(targetEntity=MainMenuChild::class, mappedBy="MainMenuCategory",)
     */
    private $mainMenuChildren;

    /**
     * @ORM\OneToMany(targetEntity=MainMenuSubCategory::class, mappedBy="MainMenuCategory", )
     */
    private $mainMenuSubCategories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hidden;

    public function __construct()
    {
        $this->mainMenuChildren = new ArrayCollection();
        $this->mainMenuSubCategories = new ArrayCollection();
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

    public function getUser(): ?Account
    {
        return $this->User;
    }

    public function setUser(?Account $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection|MainMenuChild[]
     */
    public function getMainMenuChildren(): Collection
    {
        return $this->mainMenuChildren;
    }

    public function addMainMenuChild(MainMenuChild $mainMenuChild): self
    {
        if (!$this->mainMenuChildren->contains($mainMenuChild)) {
            $this->mainMenuChildren[] = $mainMenuChild;
            $mainMenuChild->setMainMenuCategory($this);
        }

        return $this;
    }

    public function removeMainMenuChild(MainMenuChild $mainMenuChild): self
    {
        if ($this->mainMenuChildren->contains($mainMenuChild)) {
            $this->mainMenuChildren->removeElement($mainMenuChild);
            // set the owning side to null (unless already changed)
            if ($mainMenuChild->getMainMenuCategory() === $this) {
                $mainMenuChild->setMainMenuCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MainMenuSubCategory[]
     */
    public function getMainMenuSubCategories(): Collection
    {
        return $this->mainMenuSubCategories;
    }

    public function addMainMenuSubCategory(MainMenuSubCategory $mainMenuSubCategory): self
    {
        if (!$this->mainMenuSubCategories->contains($mainMenuSubCategory)) {
            $this->mainMenuSubCategories[] = $mainMenuSubCategory;
            $mainMenuSubCategory->setMainMenuCategory($this);
        }

        return $this;
    }

    public function removeMainMenuSubCategory(MainMenuSubCategory $mainMenuSubCategory): self
    {
        if ($this->mainMenuSubCategories->contains($mainMenuSubCategory)) {
            $this->mainMenuSubCategories->removeElement($mainMenuSubCategory);
            // set the owning side to null (unless already changed)
            if ($mainMenuSubCategory->getMainMenuCategory() === $this) {
                $mainMenuSubCategory->setMainMenuCategory(null);
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
