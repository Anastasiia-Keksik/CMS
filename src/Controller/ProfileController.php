<?php


namespace App\Controller;

use App\Entity\Account;
use App\Entity\GalleryPhotos;
use App\Entity\SocialPost;
use App\Entity\SocialPostComment;
use App\Repository\AccountRepository;
use App\Repository\ComicRepository;
use App\Repository\ContactRepository;
use App\Repository\GalleryPhotosRepository;
use App\Repository\ProfileDesignSettingsRepository;
use App\Repository\SocialPostRepository;
use App\Repository\UserForumPostRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocialPostCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Intervention\Image\ImageManager;

class ProfileController extends AbstractController
{
    private $theme;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])) {

            $this->theme = $_COOKIE["theme"];

        } else {

            $this->theme = "#";

        }
    }

    /**
     * @Route("/sendNewPost", name="app_sendNewPost")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function sendNewPost(Request $request, EntityManagerInterface $em)
    {

        $csrf = $request->request->get('csrf_token');

        if ($request->request->get('NewSocialPost') == "") {
            //todo: validation if empty post
        }
        //check if token is valid and if post is not null
        if ($this->isCsrfTokenValid('newpost', $csrf)) {
            $csrf = $request->request->get('wallpaper');

            $user = $this->getUser();

            $SP = new SocialPost();
            $SP->setContent($request->request->get('NewSocialPost'));
            $SP->setAccount($user);
            $SP->setCreatedAt(new \DateTime());
            $SP->setSoftDelete('0');
            $SP->setLikes('0');
            if ($request->request->get('wallpaper')) {


                $image = $request->request->get('wallpaper');
                $width = getimagesize($image)[0];
                $height = getimagesize($image)[1];
                $ratio = $height / $width;

                //dd(getimagesize($image));

                $data = explode(',', $image);
                $contentFile = base64_decode($data[1]);

                $destination = $this->getParameter('kernel.project_dir') . "/public/upload/social/BackGrounds/" . $user->getUsername();
                $newFilename = uniqid() . '.' . $request->request->get('wallpaperExt');

                if (!file_exists($destination)) {
                    mkdir($destination);
                }

                $imm = new ImageManager(array('driver' => 'gd'));

                $img = $imm->make($contentFile);

                if ($width > $height) {
                    if ($width > 1920) {
                        $width = 1920;
                        $height = ceil($height * (1920 / $width));
                    }
                } elseif ($height > $width) {
                    if ($height > 1080) {
                        $width = ceil($width * (1080 / $height));
                        $height = 1080;
                    }
                } else {
                    if ($width > 1920) {
                        $width = 1920;
                        $height = 1920;
                    }
                }

                $img->resize($width, $height);

                $img->save("upload/social/BackGrounds/" . $user->getUsername() . '/' . $newFilename);

                $SP->setBackgroundFilename($newFilename);
            } else {
                //invalid token
            }

            $em->persist($SP);
            $em->flush();
        }

        return $this->redirectToRoute('app_profile', ['profile' => $user->getUsername()]);
    }

    /**
     * @Route("/profile", name="app_main_profile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function viewMineProfile()
    {
        return new RedirectResponse($this->generateUrl('app_profile', ['profile' => $this->getUser()->getUsername()]));
    }

    /**
     * @Route("/profile/{profile}", name="app_profile")
     * @Security("is_granted('ROLE_USER')")
     * @param $profile
     * @param EntityManagerInterface $entityManager
     * @param MainMenuService $mainMenuService
     * @param Request $request
     * @param SocialPostCommentRepository $underCommentsRepo
     * @param ProfileDesignSettingsRepository $pdsr
     * @param SocialPostRepository $postRepository
     * @param ContactRepository $contactRepo
     * @param AccountRepository $accRepo
     * @param GalleryPhotosRepository $galleryPhotosRepository
     * @param UserPrivateForumRepository $forumRepository
     * @param ComicRepository $comicsRepo
     * @param GetContactsService $getContactsService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewProfile($profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService,
                                Request $request, SocialPostCommentRepository $underCommentsRepo,
                                ProfileDesignSettingsRepository $pdsr, SocialPostRepository $postRepository, ContactRepository $contactRepo,
                                AccountRepository $accRepo, GalleryPhotosRepository $galleryPhotosRepository,
                                UserPrivateForumRepository $forumRepository, ComicRepository $comicsRepo, GetContactsService $getContactsService)
    {
        $mainMenu = $mainMenuService->getMenu();
        $repository = $entityManager->getRepository(Account::class);

        $comics = $comicsRepo->findAll();

        $userCredentials = $repository->findOneBy(['username' => $profile]);
        $user = $this->getUser();

        $design = $pdsr->find($user);

        if (!$userCredentials) {
            throw $this->createNotFoundException('Nie znaleziono takiego uzytkownika');
        }
        $tab = !empty($request->query->get('tab')) ? $request->query->get('tab') : "profile";
        $avatar = !empty($request->query->get('avatar')) ? $request->query->get('avatar') : null;

        if ($userCredentials->getGallery()) {
            $photos = $galleryPhotosRepository->findLast15($userCredentials->getGallery()->getId());
        } else {
            $photos = null;
        }

        if ($userCredentials == $user) {
            $albums = $userCredentials->getGallery()->getGalleryAlbums();
        }else{
            $albums = null;
        }

        $socialPosts = $postRepository->loadNewPosts($userCredentials->getId());

        $posts = [];
        $postsIt = 0;

        foreach ($socialPosts as $post) {
            if ($post->getSoftDelete() == false) {
                $comments_length = $underCommentsRepo->countAllComments($post->getId());
                $main_comments_length = $underCommentsRepo->countMainComments($post->getId());
                $posts[$postsIt] = [
                    'Id' => $post->getId(),
                    'Content' => $post->getContent(),
                    'Author' => $post->getAccount()->getId(),
                    'AuthorUsername' => $post->getAccount()->getUsername(),
                    'AuthorFirstName' => $post->getAccount()->getFirstName(),
                    'AuthorLastName' => $post->getAccount()->getLastName(),
                    'AuthorAvatarFileName' => $post->getAccount()->getAvatarFileName(),
                    'AuthorOccupation' => $post->getAccount()->getOccupation(),
                    'createdAt' => $post->getCreatedAt(),
                    'modifiedAt' => $post->getModifiedAt(),
                    'Likes' => $post->getLikes(),
                    'Comments' => [],
                    'Comments_length' => $comments_length[0]['1'],
                    'MainCommentsLength' => $main_comments_length[0]['1'],
                    'BGFilename' => $post->getBackgroundFilename()
                ];
                $commentIt = 0;
                $comments = $underCommentsRepo->findNewestComments($post->getId(), '3');
                foreach ($comments as $comment) {
                    if ($comment->getSoftDelete() == false and $comment->getUnderAnotherComment() == null) {
                        array_push($posts[$postsIt]['Comments'], [
                            'Id' => $comment->getId(),
                            'Content' => $comment->getContent(),
                            'Author' => $comment->getAuthor()->getId(),
                            'AuthorUsername' => $comment->getAuthor()->getUsername(),
                            'AuthorFirstName' => $comment->getAuthor()->getFirstName(),
                            'AuthorLastName' => $comment->getAuthor()->getLastName(),
                            'AuthorAvatarFileName' => $comment->getAuthor()->getAvatarFileName(),
                            'createdAt' => $comment->getCreatedAt(),
                            'modifiedAt' => $comment->getModifiedAt(),
                            'CommentConversation' => []
                        ]);

                        $underComments = $underCommentsRepo->findBy(['underAnotherComment' => $comment->getId()]);
                        foreach ($underComments as $underComment) {
                            array_push($posts[$postsIt]['Comments'][$commentIt]['CommentConversation'], [
                                'Id' => $underComment->getId(),
                                'Content' => $underComment->getContent(),
                                'Author' => $underComment->getAuthor()->getId(),
                                'AuthorUsername' => $underComment->getAuthor()->getUsername(),
                                'AuthorFirstName' => $underComment->getAuthor()->getFirstName(),
                                'AuthorLastName' => $underComment->getAuthor()->getLastName(),
                                'AuthorAvatarFileName' => $underComment->getAuthor()->getAvatarFileName(),
                                'createdAt' => $underComment->getCreatedAt(),
                                'modifiedAt' => $underComment->getModifiedAt()
                            ]);
                        }
                    }
                    $commentIt++;
                }
                $postsIt++;
            }
        }

        $contacts = $getContactsService->getContacts($userCredentials->getId());


        $forum = $forumRepository->findOneBy(['UserAdmin' => $userCredentials, 'softDelete' => 0]);

        return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/profile/blank.html.twig", [
            'title' => 'title',
            'lang' => 'pl',
            'APP_NAME' => $_SERVER['APP_NAME'],
            'logoSite' => $_SERVER['SHOW_LOGO'],
            'navFooter' => $_SERVER['NAV_FOOTER'],
            'footer' => $_SERVER['FOOTER'],
            'pageName' => "Profile",
            'MainMenu' => $mainMenu,
            'profile' => $userCredentials,
            'user' => $user,
            'tab' => $tab,
            'posts' => $posts,
            'design' => $design,
            'Contacts' => $contacts,
            'gallery' => $photos,
            'SOCIAL_POSTS' => $_SERVER['SOCIAL_POSTS'],
            'theme' => $this->theme,
            'forum' => $forum,
            'comics' => $comics,
            'avatar' => $avatar,
            'albums' => $albums,
            'bgopen' => $request->query->get('bg')
        ]);
    }

    /**
     * @Route("/api/uploadWallpaper/{user}", name="api-upload-wallpaper")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadWallpaper(Request $request)
    {
        $file = $request->files->get('wallpaper');
        $fileType = $_FILES['wallpaper']['type'];
        $fileExt = $file->guessExtension();
        $fileContent = file_get_contents($_FILES['wallpaper']['tmp_name']);
        $dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);

        $json = array(
            'Ext' => $fileExt,
            'dataUrl' => $dataUrl
        );

        return new JsonResponse($json);

    }

    /**
     * @Route("/addCommentToPost/{id}", name="app_addComment")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param SocialPost $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function addComment(Request $request, SocialPost $id, EntityManagerInterface $em)
    {
        $token = $request->request->get('_token');

        if ($request->request->get('Comment_content') == "") {
            //TODO: validation when empty comment
        }

        if ($this->isCsrfTokenValid('comment', $token)) {
            $newComment = new SocialPostComment();
            $newComment->setAuthor($this->getUser());
            $newComment->setContent($request->request->get('Comment_content'));
            $newComment->setCreatedAt(new \DateTime('now'));
            $newComment->setSoftDelete('0');
            $newComment->setUnderAnotherComment(null);

            $id->addSocialPostComment($newComment);

            $em->persist($newComment);
            $em->persist($id);
            $em->flush();
        } else {
            //TODO: bad csrf
        }
        return $this->redirectToRoute('app_profile', ['profile' => $request->request->get('profile_name')]);
    }


    /**
     * @Route("/addLike", name="app_addLike")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param SocialPostRepository $postRepository
     * @param EntityManagerInterface $em
     */
    public function likes(Request $request, SocialPostRepository $postRepository, EntityManagerInterface $em)
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('like', $token)) {
            $id = $request->request->get('postId');

            $post = $postRepository->find($id);

            if ($post) {

                $em->persist($post->setLikes($post->getLikes() + 1));
                $em->flush();
            } else {
                //sory gowno lajkujesz cos czego nie ma
            }

        }
    }

    /**
     * @Route("/setProfileBackground/{profile}", name="app_setProfileBackground")
     * @Security("is_granted('ROLE_USER')")
     * @param Account $profile
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|RedirectResponse
     */
    public function setProfileBackground(Account $profile, Request $request, EntityManagerInterface $em)
    {
        if ($profile->getId() == $this->getUser()->getId()) {
            $fileName = $request->request->get('fileName');

            $profile->setBackgroundFileName($fileName);

            $em->persist($profile);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_main_profile'));
        } else {
            return new JsonResponse(['error' => 'You are not the emperor of this account']);
        }
    }

    /**
     * @Route("/deleteProfileBackground/{profile}", name="app_deleteProfileBackground")
     * @Security("is_granted('ROLE_USER')")
     * @param Account $profile
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|RedirectResponse
     */
    public function deleteProfileBackground(Account $profile, Request $request, EntityManagerInterface $em)
    {
        if ($profile->getId() == $this->getUser()->getId()) {

            $profile->setBackgroundFileName('');

            $em->persist($profile);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_main_profile'));
        } else {
            return new JsonResponse(['error' => 'You are not the emperor of this account']);
        }
    }

    /**
     * @Route("/setSentProfileBackground/{profile}", name="app_setSentProfileBackground")
     * @Security("is_granted('ROLE_USER')")
     * @param Account $profile
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse|RedirectResponse
     */
    public function setSentProfileBackground(Account $profile, Request $request, EntityManagerInterface $em)
    {
        /**
        * @var UploadedFile $uploadedFile
        */
        if ($profile->getId() == $this->getUser()->getId()) {
            $uploadedFile = $request->files->get('file');
            $destination = $this->getParameter('kernel.project_dir') . "/public/upload/gallery/" . $profile->getUsername();

            if ($uploadedFile->getClientOriginalExtension() !== 'jpg' and $uploadedFile->getClientOriginalExtension() !== 'gif'
                and $uploadedFile->getClientOriginalExtension() !== 'png' and $uploadedFile->getClientOriginalExtension() !== 'jpeg'){
                Die('wrong format: .'.$uploadedFile->getClientOriginalExtension());
            }

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName() . '.' . $uploadedFile->getClientOriginalExtension(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();

             if($uploadedFile->getSize() > 2000000)
             {
                 $this->addFlash('error', "file was to big");
                 return $this->redirectToRoute("app_main_profile", ['tab'=>'settings']);
             }

            $uploadedFile->move($destination, $newFilename);

            $img = new GalleryPhotos();

            $img->setUnderGalleryId($profile->getGallery());
            $img->setFileName($newFilename);
            $img->setOriginalFilename($originalFilename);
            $img->setUploadedAt(new \DateTime());
            $img->setSoftDelete(0);

            $profile->setBackgroundFileName($newFilename);

            $em->persist($img);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_profile', ['profile'=>$profile->getUsername(), 'bg'=>'open']));
        } else {
            return new JsonResponse(['error' => 'You are not the emperor of this account']);
        }
    }

    /**
     * @Route("/setProfileBackgroundPosition/{profile}", name="app_setProfileBackgroundPosition")
     * @Security("is_granted('ROLE_USER')")
     * @param Account $profile
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function setProfileBackgroundPosition(Account $profile, Request $request, EntityManagerInterface $em)
    {
        if ($profile->getId() == $this->getUser()->getId()) {
            $position = $request->request->get('position');

            if ($position < 0 or $position > 100) {
                die('value of position needs to be between 0 and 100.');
            }

            $profile->setBgPosition($position);

            $em->persist($profile);
            $em->flush();

            return new JsonResponse(['success' => 'ok']);
        } else {
            return new JsonResponse(['error' => 'You are not the emperor of this account']);
        }
    }
}