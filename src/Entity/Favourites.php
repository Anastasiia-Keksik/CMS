<?php

namespace App\Entity;

use App\Repository\FavouritesRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=FavouritesRepository::class)
 */
class Favourites
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=SocialPost::class, inversedBy="yes")
     */
    private $SocialPost;

    /**
     * @ORM\ManyToOne(targetEntity=SocialPostComment::class, inversedBy="favourites")
     */
    private $SocialPostComment;

    /**
     * @ORM\ManyToOne(targetEntity=PostsLikes::class, inversedBy="favourites")
     */
    private $PostsLikes;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumTopic::class, inversedBy="favourites")
     */
    private $ForumTopic;

    /**
     * @ORM\ManyToOne(targetEntity=UserForumPost::class, inversedBy="favourites")
     */
    private $ForumPosts;

    /**
     * @ORM\ManyToOne(targetEntity=GalleryPhotos::class, inversedBy="favourites")
     */
    private $GalleryPhotos;

    /**
     * @ORM\ManyToOne(targetEntity=ComicEpisode::class, inversedBy="favourites")
     */
    private $ComicEpisodes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): ?int
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

    public function getSocialPost(): ?SocialPost
    {
        return $this->SocialPost;
    }

    public function setSocialPost(?SocialPost $SocialPost): self
    {
        $this->SocialPost = $SocialPost;

        return $this;
    }

    public function getSocialPostComment(): ?SocialPostComment
    {
        return $this->SocialPostComment;
    }

    public function setSocialPostComment(?SocialPostComment $SocialPostComment): self
    {
        $this->SocialPostComment = $SocialPostComment;

        return $this;
    }

    public function getPostsLikes(): ?PostsLikes
    {
        return $this->PostsLikes;
    }

    public function setPostsLikes(?PostsLikes $PostsLikes): self
    {
        $this->PostsLikes = $PostsLikes;

        return $this;
    }

    public function getForumTopic(): ?ForumTopic
    {
        return $this->ForumTopic;
    }

    public function setForumTopic(?ForumTopic $ForumTopic): self
    {
        $this->ForumTopic = $ForumTopic;

        return $this;
    }

    public function getForumPosts(): ?ForumPost
    {
        return $this->ForumPosts;
    }

    public function setForumPosts(?ForumPost $ForumPosts): self
    {
        $this->ForumPosts = $ForumPosts;

        return $this;
    }

    public function getGalleryPhotos(): ?GalleryPhotos
    {
        return $this->GalleryPhotos;
    }

    public function setGalleryPhotos(?GalleryPhotos $GalleryPhotos): self
    {
        $this->GalleryPhotos = $GalleryPhotos;

        return $this;
    }

    public function getComicEpisodes(): ?ComicEpisode
    {
        return $this->ComicEpisodes;
    }

    public function setComicEpisodes(?ComicEpisode $ComicEpisodes): self
    {
        $this->ComicEpisodes = $ComicEpisodes;

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
