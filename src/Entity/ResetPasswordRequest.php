<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResetPasswordRequestRepository")
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $user;

    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);

        $this->id = Uuid::uuid4();
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUser(?Account $user): self
    {
        $this->user = $user;

        return $this;
    }
}
