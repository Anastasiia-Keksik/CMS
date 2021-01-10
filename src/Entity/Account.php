<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Username already in use"
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Email already in use"
 * )
 */
class Account implements UserInterface
{
    /**
     * @var string
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     * @Groups({"notification"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"notification"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"notification"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"notification"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=160, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $paypal;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastOnline;

    /**
     * @ORM\OneToMany(targetEntity=MainMenuCategory::class, mappedBy="User")
     */
    private $mainMenuCategories;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $reddit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skype;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $flickr;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $newsletter;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $template;

    /**
     * @ORM\Column(type="datetime")
     */
    private $agreedTermsAt;

    /**
     * @ORM\OneToMany(targetEntity=ForumTopic::class, mappedBy="author")
     */
    private $forumTopics;

    /**
     * @ORM\OneToMany(targetEntity=ForumPost::class, mappedBy="Author")
     */
    private $forumPosts;

    /**
     * @ORM\OneToMany(targetEntity=ForumPostConversation::class, mappedBy="author")
     */
    private $forumPostConversations;


    //DODANE PRZEZE MNIE
    /**
     * @ORM\OneToMany(targetEntity=UserForumTopic::class, mappedBy="author")
     */
    private $userForumTopics;

    /**
     * @ORM\OneToMany(targetEntity=UserForumPost::class, mappedBy="Author")
     */
    private $userForumPosts;

    /**
     * @ORM\OneToMany(targetEntity=UserForumPostConversation::class, mappedBy="author")
     */
    private $userForumPostConversations;

    //END DODANE PRZEZE MNIE

    /**
     * @ORM\OneToMany(targetEntity=UserPrivateForum::class, mappedBy="UserAdmin", cascade={"persist", "remove"})
     */
    private $userPrivateForum;

//    /**
//     * @ORM\ManyToMany(targetEntity=UserPrivateForum::class, mappedBy="Members")
//     */
//    private $userPrivateForums;

    /**
     * @ORM\OneToOne(targetEntity=Gallery::class, mappedBy="account", cascade={"persist", "remove"})
     */
    private $gallery;

    /**
     * @ORM\OneToMany(targetEntity=IpLastLogonLog::class, mappedBy="User", orphanRemoval=true)
     */
    private $IP;

    /**
     * @ORM\OneToMany(targetEntity=SocialPost::class, mappedBy="Account", orphanRemoval=true)
     */
    private $socialPosts;

    /**
     * @ORM\OneToOne(targetEntity=AccountSignature::class, mappedBy="Account", cascade={"persist", "remove"})
     */
    private $Signature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"notification"})
     */
    private $avatarFileName;

    /**
     * @ORM\OneToMany(targetEntity=SocialPostComment::class, mappedBy="author")
     */
    private $socialPostComments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Occupation;

    /**
     * @ORM\OneToOne(targetEntity=ProfileDesignSettings::class, mappedBy="Account", cascade={"persist", "remove"})
     */
    private $profileDesignSettings;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="Account_source", orphanRemoval=true)
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="Account_Targets", orphanRemoval=true)
     */
    private $contacts_backward;

    /**
     * @ORM\OneToMany(targetEntity=PostsLikes::class, mappedBy="User")
     */
    private $postsLikes;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $themeDoL;

    /**
     * @ORM\OneToMany(targetEntity=ComicSubscriptions::class, mappedBy="UserId", orphanRemoval=true)
     */
    private $comicSubscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Comic::class, mappedBy="Author")
     */
    private $comics;

    /**
     * @ORM\OneToOne(targetEntity=Wallet::class, mappedBy="User", cascade={"persist", "remove"})
     */
    private $wallet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $BackgroundFileName;

    /**
     * @ORM\Column(type="smallint")
     */
    private $bgPosition = 0;

    /**
     * @ORM\OneToMany(targetEntity=ForumMembers::class, mappedBy="Member", orphanRemoval=true)
     */
    private $forumsMember;

    /**
     * @ORM\ManyToMany(targetEntity=UserForumRanks::class, mappedBy="User")
     */
    private $userForumRanks;

    /**
     * @ORM\OneToMany(targetEntity=Favourites::class, mappedBy="User", orphanRemoval=true)
     */
    private $favourite;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $stripeCustomerId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $stripeAccountPending;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $StripeAccountId;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bday;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $bmonth;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $byear;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $line1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $line2;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="smallint")
     */
    private $VisibleName;

    /**
     * @ORM\OneToMany(targetEntity=ProjectUserConnection::class, mappedBy="User")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=InvitationRequest::class, mappedBy="WhoInvite", orphanRemoval=true)
     */
    private $invitationRequests;

    /**
     * @ORM\OneToMany(targetEntity=InvitationRequest::class, mappedBy="WhoInvited", orphanRemoval=true)
     */
    private $invitationRequiestsReceived;

    /**
     * @ORM\OneToMany(targetEntity=Circles::class, mappedBy="User", orphanRemoval=true)
     */
    private $circles;

    public function __construct()
    {
        $this->mainMenuCategories = new ArrayCollection();
        $this->forumTopics = new ArrayCollection();
        $this->forumPosts = new ArrayCollection();
        $this->forumPostConversations = new ArrayCollection();
        $this->userForumTopics = new ArrayCollection();
        $this->userForumPosts = new ArrayCollection();
        $this->userForumPostConversations = new ArrayCollection();
//        $this->userPrivateForums = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->IP = new ArrayCollection();
        $this->socialPosts = new ArrayCollection();
        $this->socialPostComments = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->contacts_backward = new ArrayCollection();
        $this->PostsLikes = new ArrayCollection();
        $this->postsLikes = new ArrayCollection();
        $this->comicSubscriptions = new ArrayCollection();
        $this->comics = new ArrayCollection();

        $this->id = Uuid::uuid4();
        $this->userPrivateForum = new ArrayCollection();
        $this->forumsMember = new ArrayCollection();
        $this->userForumRanks = new ArrayCollection();
        $this->favourite = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->invitationRequests = new ArrayCollection();
        $this->invitationRequiestsReceived = new ArrayCollection();
        $this->circles = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPaypal(): ?string
    {
        return $this->paypal;
    }

    public function setPaypal(?string $paypal): self
    {
        $this->paypal = $paypal;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

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

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getLastOnline(): ?\DateTimeInterface
    {
        return $this->lastOnline;
    }

    public function setLastOnline(?\DateTimeInterface $lastOnline): self
    {
        $this->lastOnline = $lastOnline;

        return $this;
    }

    /**
     * @return Collection|MainMenuCategory[]
     */
    public function getMainMenuCategories(): Collection
    {
        return $this->mainMenuCategories;
    }

    public function addMainMenuCategory(MainMenuCategory $mainMenuCategory): self
    {
        if (!$this->mainMenuCategories->contains($mainMenuCategory)) {
            $this->mainMenuCategories[] = $mainMenuCategory;
            $mainMenuCategory->setUser($this);
        }

        return $this;
    }

    public function removeMainMenuCategory(MainMenuCategory $mainMenuCategory): self
    {
        if ($this->mainMenuCategories->contains($mainMenuCategory)) {
            $this->mainMenuCategories->removeElement($mainMenuCategory);
            // set the owning side to null (unless already changed)
            if ($mainMenuCategory->getUser() === $this) {
                $mainMenuCategory->setUser(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getReddit(): ?string
    {
        return $this->reddit;
    }

    public function setReddit(?string $reddit): self
    {
        $this->reddit = $reddit;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    public function getFlickr(): ?string
    {
        return $this->flickr;
    }

    public function setFlickr(?string $flickr): self
    {
        $this->flickr = $flickr;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(?bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function terms(): self
    {
        $this->agreedTermsAt = new \DateTime();

        return $this;
    }

    /**
     * @return Collection|ForumTopic[]
     */
    public function getForumTopics(): Collection
    {
        return $this->forumTopics;
    }

    public function addForumTopic(ForumTopic $forumTopic): self
    {
        if (!$this->forumTopics->contains($forumTopic)) {
            $this->forumTopics[] = $forumTopic;
            $forumTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeForumTopic(ForumTopic $forumTopic): self
    {
        if ($this->forumTopics->contains($forumTopic)) {
            $this->forumTopics->removeElement($forumTopic);
            // set the owning side to null (unless already changed)
            if ($forumTopic->getAuthor() === $this) {
                $forumTopic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumPost[]
     */
    public function getForumPosts(): Collection
    {
        return $this->forumPosts;
    }

    public function addForumPost(ForumPost $forumPost): self
    {
        if (!$this->forumPosts->contains($forumPost)) {
            $this->forumPosts[] = $forumPost;
            $forumPost->setAuthor($this);
        }

        return $this;
    }

    public function removeForumPost(ForumPost $forumPost): self
    {
        if ($this->forumPosts->contains($forumPost)) {
            $this->forumPosts->removeElement($forumPost);
            // set the owning side to null (unless already changed)
            if ($forumPost->getAuthor() === $this) {
                $forumPost->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumPostConversation[]
     */
    public function getForumPostConversations(): Collection
    {
        return $this->forumPostConversations;
    }

    public function addForumPostConversation(ForumPostConversation $forumPostConversation): self
    {
        if (!$this->forumPostConversations->contains($forumPostConversation)) {
            $this->forumPostConversations[] = $forumPostConversation;
            $forumPostConversation->setAuthor($this);
        }

        return $this;
    }

    public function removeForumPostConversation(ForumPostConversation $forumPostConversation): self
    {
        if ($this->forumPostConversations->contains($forumPostConversation)) {
            $this->forumPostConversations->removeElement($forumPostConversation);
            // set the owning side to null (unless already changed)
            if ($forumPostConversation->getAuthor() === $this) {
                $forumPostConversation->setAuthor(null);
            }
        }

        return $this;
    }

    //DODANE PRZEZE MNIE

    /**
     * @return Collection|UserForumTopic[]
     */
    public function getUserForumTopics(): Collection
    {
        return $this->userForumTopics;
    }

    public function addUserForumTopic(UserForumTopic $userForumTopic): self
    {
        if (!$this->userForumTopics->contains($userForumTopic)) {
            $this->userForumTopics[] = $userForumTopic;
            $userForumTopic->setAuthor($this);
        }

        return $this;
    }

    public function removeUserForumTopic(UserForumTopic $userForumTopic): self
    {
        if ($this->userForumTopics->contains($userForumTopic)) {
            $this->userForumTopics->removeElement($userForumTopic);
            // set the owning side to null (unless already changed)
            if ($userForumTopic->getAuthor() === $this) {
                $userForumTopic->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserForumPost[]
     */
    public function getUserForumPosts(): Collection
    {
        return $this->userForumPosts;
    }

    public function addUserForumPost(UserForumPost $userForumPost): self
    {
        if (!$this->userForumPosts->contains($userForumPost)) {
            $this->userForumPosts[] = $userForumPost;
            $userForumPost->setAuthor($this);
        }

        return $this;
    }

    public function removeUserForumPost(UserForumPost $userForumPost): self
    {
        if ($this->userForumPosts->contains($userForumPost)) {
            $this->userForumPosts->removeElement($userForumPost);
            // set the owning side to null (unless already changed)
            if ($userForumPost->getAuthor() === $this) {
                $userForumPost->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserForumPostConversation[]
     */
    public function getUserForumPostConversations(): Collection
    {
        return $this->forumPostConversations;
    }

    public function addUserForumPostConversation(UserForumPostConversation $userForumPostConversation): self
    {
        if (!$this->userForumPostConversations->contains($userForumPostConversation)) {
            $this->userForumPostConversations[] = $userForumPostConversation;
            $userForumPostConversation->setAuthor($this);
        }

        return $this;
    }

    public function removeUserForumPostConversation(UserForumPostConversation $userForumPostConversation): self
    {
        if ($this->userForumPostConversations->contains($userForumPostConversation)) {
            $this->userForumPostConversations->removeElement($userForumPostConversation);
            // set the owning side to null (unless already changed)
            if ($userForumPostConversation->getAuthor() === $this) {
                $userForumPostConversation->setAuthor(null);
            }
        }

        return $this;
    }

    //END DODANE PRZE ZE MNIE

    /**
     * @return Collection|UserPrivateForum[]
     */
    public function getUserPrivateForum(): Collection
    {
        return $this->userPrivateForum;
    }

    public function setUserPrivateForum(UserPrivateForum $userPrivateForum): self
    {
        $this->userPrivateForum = $userPrivateForum;

        // set the owning side of the relation if necessary
        if ($userPrivateForum->getUserAdmin() !== $this) {
            $userPrivateForum->setUserAdmin($this);
        }

        return $this;
    }

//    /**
//     * @return Collection|UserPrivateForum[]
//     */
//    public function getUserPrivateForums(): Collection
//    {
//        return $this->userPrivateForums;
//    }
//
//    public function addUserPrivateForum(UserPrivateForum $userPrivateForum): self
//    {
//        if (!$this->userPrivateForums->contains($userPrivateForum)) {
//            $this->userPrivateForums[] = $userPrivateForum;
//            $userPrivateForum->addMember($this);
//        }
//
//        return $this;
//    }
//
//    public function removeUserPrivateForum(UserPrivateForum $userPrivateForum): self
//    {
//        if ($this->userPrivateForums->contains($userPrivateForum)) {
//            $this->userPrivateForums->removeElement($userPrivateForum);
//            $userPrivateForum->removeMember($this);
//        }
//
//        return $this;
//    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(Gallery $gallery): self
    {
        $this->gallery = $gallery;

        // set the owning side of the relation if necessary
        if ($gallery->getAccount() !== $this) {
            $gallery->setAccount($this);
        }

        return $this;
    }

    /**
     * @return Collection|IpLastLogonLog[]
     */
    public function getIP(): Collection
    {
        return $this->IP;
    }

    public function addIP(IpLastLogonLog $iP): self
    {
        if (!$this->IP->contains($iP)) {
            $this->IP[] = $iP;
            $iP->setUser($this);
        }

        return $this;
    }

    public function removeIP(IpLastLogonLog $iP): self
    {
        if ($this->IP->contains($iP)) {
            $this->IP->removeElement($iP);
            // set the owning side to null (unless already changed)
            if ($iP->getUser() === $this) {
                $iP->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SocialPost[]
     */
    public function getSocialPosts(): Collection
    {
        return $this->socialPosts;
    }

    public function addSocialPost(SocialPost $socialPost): self
    {
        if (!$this->socialPosts->contains($socialPost)) {
            $this->socialPosts[] = $socialPost;
            $socialPost->setAccount($this);
        }

        return $this;
    }

    public function removeSocialPost(SocialPost $socialPost): self
    {
        if ($this->socialPosts->contains($socialPost)) {
            $this->socialPosts->removeElement($socialPost);
            // set the owning side to null (unless already changed)
            if ($socialPost->getAccount() === $this) {
                $socialPost->setAccount(null);
            }
        }

        return $this;
    }

    public function getSignature(): ?AccountSignature
    {
        return $this->Signature;
    }

    public function setSignature(AccountSignature $Signature): self
    {
        $this->Signature = $Signature;

        // set the owning side of the relation if necessary
        if ($Signature->getAccount() !== $this) {
            $Signature->setAccount($this);
        }

        return $this;
    }

    public function getAvatarFileName(): ?string
    {
        return $this->avatarFileName;
    }

    public function setAvatarFileName(?string $avatarFileName): self
    {
        $this->avatarFileName = $avatarFileName;

        return $this;
    }

    /**
     * @return Collection|SocialPostComment[]
     */
    public function getSocialPostComments(): Collection
    {
        return $this->socialPostComments;
    }

    public function addSocialPostComment(SocialPostComment $socialPostComment): self
    {
        if (!$this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments[] = $socialPostComment;
            $socialPostComment->setAuthor($this);
        }

        return $this;
    }

    public function removeSocialPostComment(SocialPostComment $socialPostComment): self
    {
        if ($this->socialPostComments->contains($socialPostComment)) {
            $this->socialPostComments->removeElement($socialPostComment);
            // set the owning side to null (unless already changed)
            if ($socialPostComment->getAuthor() === $this) {
                $socialPostComment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getOccupation(): ?string
    {
        return $this->Occupation;
    }

    public function setOccupation(?string $Occupation): self
    {
        $this->Occupation = $Occupation;

        return $this;
    }

    public function getProfileDesignSettings(): ?ProfileDesignSettings
    {
        return $this->profileDesignSettings;
    }

    public function setProfileDesignSettings(ProfileDesignSettings $profileDesignSettings): self
    {
        $this->profileDesignSettings = $profileDesignSettings;

        // set the owning side of the relation if necessary
        if ($profileDesignSettings->getAccount() !== $this) {
            $profileDesignSettings->setAccount($this);
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setAccountSource($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getAccountSource() === $this) {
                $contact->setAccountSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContactsBackward(): Collection
    {
        return $this->contacts_backward;
    }

    public function addContactsBackward(Contact $contactsBackward): self
    {
        if (!$this->contacts_backward->contains($contactsBackward)) {
            $this->contacts_backward[] = $contactsBackward;
            $contactsBackward->setAccountTargets($this);
        }

        return $this;
    }

    public function removeContactsBackward(Contact $contactsBackward): self
    {
        if ($this->contacts_backward->contains($contactsBackward)) {
            $this->contacts_backward->removeElement($contactsBackward);
            // set the owning side to null (unless already changed)
            if ($contactsBackward->getAccountTargets() === $this) {
                $contactsBackward->setAccountTargets(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostsLikes[]
     */
    public function getPostsLikes(): Collection
    {
        return $this->postsLikes;
    }

    public function addPostsLike(PostsLikes $postsLike): self
    {
        if (!$this->postsLikes->contains($postsLike)) {
            $this->postsLikes[] = $postsLike;
            $postsLike->setUser($this);
        }

        return $this;
    }

    public function removePostsLike(PostsLikes $postsLike): self
    {
        if ($this->postsLikes->contains($postsLike)) {
            $this->postsLikes->removeElement($postsLike);
            // set the owning side to null (unless already changed)
            if ($postsLike->getUser() === $this) {
                $postsLike->setUser(null);
            }
        }

        return $this;
    }

    public function getThemeDoL(): ?int
    {
        return $this->themeDoL;
    }

    public function setThemeDoL(?int $themeDoL): self
    {
        $this->themeDoL = $themeDoL;

        return $this;
    }

    /**
     * @return Collection|ComicSubscriptions[]
     */
    public function getComicSubscriptions(): Collection
    {
        return $this->comicSubscriptions;
    }

    public function addComicSubscription(ComicSubscriptions $comicSubscription): self
    {
        if (!$this->comicSubscriptions->contains($comicSubscription)) {
            $this->comicSubscriptions[] = $comicSubscription;
            $comicSubscription->setUserId($this);
        }

        return $this;
    }

    public function removeComicSubscription(ComicSubscriptions $comicSubscription): self
    {
        if ($this->comicSubscriptions->contains($comicSubscription)) {
            $this->comicSubscriptions->removeElement($comicSubscription);
            // set the owning side to null (unless already changed)
            if ($comicSubscription->getUserId() === $this) {
                $comicSubscription->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comic[]
     */
    public function getComics(): Collection
    {
        return $this->comics;
    }

    public function addComic(Comic $comic): self
    {
        if (!$this->comics->contains($comic)) {
            $this->comics[] = $comic;
            $comic->setAuthor($this);
        }

        return $this;
    }

    public function removeComic(Comic $comic): self
    {
        if ($this->comics->contains($comic)) {
            $this->comics->removeElement($comic);
            // set the owning side to null (unless already changed)
            if ($comic->getAuthor() === $this) {
                $comic->setAuthor(null);
            }
        }

        return $this;
    }

    public function setAgreedTermsAt(\DateTimeInterface $agreedTermsAt): self
    {
        $this->agreedTermsAt = $agreedTermsAt;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(Wallet $wallet): self
    {
        $this->wallet = $wallet;

        // set the owning side of the relation if necessary
        if ($wallet->getUser() !== $this) {
            $wallet->setUser($this);
        }

        return $this;
    }

    public function getBackgroundFileName(): ?string
    {
        return $this->BackgroundFileName;
    }

    public function setBackgroundFileName(?string $BackgroundFileName): self
    {
        $this->BackgroundFileName = $BackgroundFileName;

        return $this;
    }

    public function getBgPosition(): ?int
    {
        return $this->bgPosition;
    }

    public function setBgPosition(int $bgPosition): self
    {
        $this->bgPosition = $bgPosition;

        return $this;
    }

    /**
     * @return Collection|ForumMembers[]
     */
    public function getForumsMember(): Collection
    {
        return $this->forumsMember;
    }

    public function addForumsMember(ForumMembers $forumsMember): self
    {
        if (!$this->forumsMember->contains($forumsMember)) {
            $this->forumsMember[] = $forumsMember;
            $forumsMember->setMember($this);
        }

        return $this;
    }

    public function removeForumsMember(ForumMembers $forumsMember): self
    {
        if ($this->forumsMember->contains($forumsMember)) {
            $this->forumsMember->removeElement($forumsMember);
            // set the owning side to null (unless already changed)
            if ($forumsMember->getMember() === $this) {
                $forumsMember->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserForumRanks[]
     */
    public function getUserForumRanks(): Collection
    {
        return $this->userForumRanks;
    }

    public function addUserForumRank(UserForumRanks $userForumRank): self
    {
        if (!$this->userForumRanks->contains($userForumRank)) {
            $this->userForumRanks[] = $userForumRank;
            $userForumRank->addUser($this);
        }

        return $this;
    }

    public function removeUserForumRank(UserForumRanks $userForumRank): self
    {
        if ($this->userForumRanks->contains($userForumRank)) {
            $this->userForumRanks->removeElement($userForumRank);
            $userForumRank->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Favourites[]
     */
    public function getFavourite(): Collection
    {
        return $this->favourite;
    }

    public function addFavourite(Favourites $Favourite): self
    {
        if (!$this->favourite->contains($Favourite)) {
            $this->favourite[] = $Favourite;
            $Favourite->setUser($this);
        }

        return $this;
    }

    public function removeFavourite(Favourites $Favourite): self
    {
        if ($this->favourite->contains($Favourite)) {
            $this->favourite->removeElement($Favourite);
            // set the owning side to null (unless already changed)
            if ($Favourite->getUser() === $this) {
                $Favourite->setUser(null);
            }
        }

        return $this;
    }

    public function getStripeCustomerId(): ?string
    {
        return $this->stripeCustomerId;
    }

    public function setStripeCustomerId(?string $stripeCustomerId): self
    {
        $this->stripeCustomerId = $stripeCustomerId;

        return $this;
    }

    public function getStripeAccountPending(): ?bool
    {
        return $this->stripeAccountPending;
    }

    public function setStripeAccountPending(?bool $stripeAccountPending): self
    {
        $this->stripeAccountPending = $stripeAccountPending;

        return $this;
    }

    public function getStripeAccountId(): ?string
    {
        return $this->StripeAccountId;
    }

    public function setStripeAccountId(?string $StripeAccountId): self
    {
        $this->StripeAccountId = $StripeAccountId;

        return $this;
    }

    public function getBday(): ?int
    {
        return $this->bday;
    }

    public function setBday(?int $bday): self
    {
        $this->bday = $bday;

        return $this;
    }

    public function getBmonth(): ?int
    {
        return $this->bmonth;
    }

    public function setBmonth(int $bmonth): self
    {
        $this->bmonth = $bmonth;

        return $this;
    }

    public function getByear(): ?int
    {
        return $this->byear;
    }

    public function setByear(?int $byear): self
    {
        $this->byear = $byear;

        return $this;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine1(?string $line1): self
    {
        $this->line1 = $line1;

        return $this;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(?string $line2): self
    {
        $this->line2 = $line2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getVisibleName(): ?int
    {
        return $this->VisibleName;
    }

    public function setVisibleName(int $VisibleName): self
    {
        $this->VisibleName = $VisibleName;

        return $this;
    }

    /**
     * @return Collection|ProjectUserConnection[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectUserConnection $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(ProjectUserConnection $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InvitationRequest[]
     */
    public function getInvitationRequests(): Collection
    {
        return $this->invitationRequests;
    }

    public function addInvitationRequest(InvitationRequest $invitationRequest): self
    {
        if (!$this->invitationRequests->contains($invitationRequest)) {
            $this->invitationRequests[] = $invitationRequest;
            $invitationRequest->setWhoInvite($this);
        }

        return $this;
    }

    public function removeInvitationRequest(InvitationRequest $invitationRequest): self
    {
        if ($this->invitationRequests->removeElement($invitationRequest)) {
            // set the owning side to null (unless already changed)
            if ($invitationRequest->getWhoInvite() === $this) {
                $invitationRequest->setWhoInvite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InvitationRequest[]
     */
    public function getInvitationRequiestsReceived(): Collection
    {
        return $this->invitationRequiestsReceived;
    }

    public function addInvitationRequiestsReceived(InvitationRequest $invitationRequiestsReceived): self
    {
        if (!$this->invitationRequiestsReceived->contains($invitationRequiestsReceived)) {
            $this->invitationRequiestsReceived[] = $invitationRequiestsReceived;
            $invitationRequiestsReceived->setWhoInvited($this);
        }

        return $this;
    }

    public function removeInvitationRequiestsReceived(InvitationRequest $invitationRequiestsReceived): self
    {
        if ($this->invitationRequiestsReceived->removeElement($invitationRequiestsReceived)) {
            // set the owning side to null (unless already changed)
            if ($invitationRequiestsReceived->getWhoInvited() === $this) {
                $invitationRequiestsReceived->setWhoInvited(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Circles[]
     */
    public function getCircles(): Collection
    {
        return $this->circles;
    }

    public function addCircle(Circles $circle): self
    {
        if (!$this->circles->contains($circle)) {
            $this->circles[] = $circle;
            $circle->setUser($this);
        }

        return $this;
    }

    public function removeCircle(Circles $circle): self
    {
        if ($this->circles->removeElement($circle)) {
            // set the owning side to null (unless already changed)
            if ($circle->getUser() === $this) {
                $circle->setUser(null);
            }
        }

        return $this;
    }
}
