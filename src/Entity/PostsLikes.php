<?php

namespace App\Entity;

use App\Repository\PostsLikesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PostsLikesRepository::class)
 */
class PostsLikes
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="postsLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumPost::class, inversedBy="postsLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Post;

    /**
     * @ORM\OneToMany(targetEntity=Favourites::class, mappedBy="PostsLikes")
     */
    private $favourites;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->favourites = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getPost(): ?UserForumPost
    {
        return $this->Post;
    }

    public function setPost(?UserForumPost $Post): self
    {
        $this->Post = $Post;

        return $this;
    }

    /**
     * @return Collection|Favourites[]
     */
    public function getFavourites(): Collection
    {
        return $this->favourites;
    }

    public function addFavourite(Favourites $favourite): self
    {
        if (!$this->favourites->contains($favourite)) {
            $this->favourites[] = $favourite;
            $favourite->setPostsLikes($this);
        }

        return $this;
    }

    public function removeFavourite(Favourites $favourite): self
    {
        if ($this->favourites->contains($favourite)) {
            $this->favourites->removeElement($favourite);
            // set the owning side to null (unless already changed)
            if ($favourite->getPostsLikes() === $this) {
                $favourite->setPostsLikes(null);
            }
        }

        return $this;
    }
}
