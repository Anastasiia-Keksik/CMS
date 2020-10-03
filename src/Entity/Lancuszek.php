<?php

namespace App\Entity;

use App\Repository\LancuszekRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=LancuszekRepository::class)
 */
class Lancuszek
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $OsobaPolecajaca;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $OsobaPolecona;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOsobaPolecajaca(): ?Account
    {
        return $this->OsobaPolecajaca;
    }

    public function setOsobaPolecajaca(?Account $OsobaPolecajaca): self
    {
        $this->OsobaPolecajaca = $OsobaPolecajaca;

        return $this;
    }

    public function getOsobaPolecona(): ?Account
    {
        return $this->OsobaPolecona;
    }

    public function setOsobaPolecona(?Account $OsobaPolecona): self
    {
        $this->OsobaPolecona = $OsobaPolecona;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
