<?php

namespace App\Entity;

use App\Repository\ComicCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComicCategoriesRepository::class)
 */
class ComicCategories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Comics::class, mappedBy="category1")
     */
    private $category2;

    public function __construct()
    {
        $this->category2 = new ArrayCollection();
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

    /**
     * @return Collection|Comics[]
     */
    public function getCategory2(): Collection
    {
        return $this->category2;
    }

    public function addCategory2(Comics $category2): self
    {
        if (!$this->category2->contains($category2)) {
            $this->category2[] = $category2;
            $category2->setCategory1($this);
        }

        return $this;
    }

    public function removeCategory2(Comics $category2): self
    {
        if ($this->category2->contains($category2)) {
            $this->category2->removeElement($category2);
            // set the owning side to null (unless already changed)
            if ($category2->getCategory1() === $this) {
                $category2->setCategory1(null);
            }
        }

        return $this;
    }
}
