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
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if (isset($_COOKIE["theme"])){

            $this->theme = $_COOKIE["theme"];

        }else{

            $this->theme = "#";

        }
    }

    /**
     * @Route("/sendNewPost", name="app_sendNewPost")
     * @Security("is_granted('ROLE_USER')")
     */
    public function sendNewPost(Request $request, EntityManagerInterface $em)
    {

        $csrf = $request->request->get('csrf_token');

        if ($request->request->get('NewSocialPost') == ""){
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
                $ratio = $height/$width;

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

                if($width > $height)
                {
                    if ($width > 1920){
                        $width = 1920;
                        $height = ceil($height * (1920/$width));
                    }
                }elseif($height > $width) {
                    if ($height > 1080){
                        $width = ceil($width * (1080/$height));
                        $height = 1080;
                    }
                }else{
                    if ($width > 1920){
                        $width = 1920;
                        $height = 1920;
                    }
                }

                $img->resize($width, $height);

                $img->save("upload/social/BackGrounds/" . $user->getUsername().'/'.$newFilename);

                $SP->setBackgroundFilename($newFilename);
            } else {
                //invalid token
            }

            $em->persist($SP);
            $em->flush();
        }

        return $this->redirectToRoute('app_profile', ['profile'=>$user->getUsername()]);
    }

    /**
     * @Route("/profile", name="app_main_profile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function viewMineProfile()
    {
        return new RedirectResponse($this->generateUrl('app_profile', ['profile'=>$this->getUser()->getUsername()]));
    }

    /**
     * @Route("/profile/{profile}", name="app_profile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function viewProfile($profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService,
                                Request $request, SocialPostCommentRepository $underCommentsRepo,
                                ProfileDesignSettingsRepository $pdsr, SocialPostRepository $postRepository, ContactRepository $contactRepo,
                                AccountRepository $accRepo, GalleryPhotosRepository $galleryPhotosRepository,
                                UserPrivateForumRepository $forumRepository, ComicRepository $comicsRepo)
    {
        $mainMenu = $mainMenuService->getMenu();
        $repository = $entityManager->getRepository(Account::class);

        $comics = $comicsRepo->findAll();

        $userCredentials = $repository->findOneBy(['username' => $profile]);
        $user = $this->getUser();

        $design = $pdsr->find($user);

        if (!$userCredentials)
        {
            throw $this->createNotFoundException('Nie znaleziono takiego uzytkownika');
        }
        $tab = !empty($request->query->get('tab')) ? $request->query->get('tab') : "profile";

        if ($userCredentials->getGallery()){
            $photos = $galleryPhotosRepository->findLast9($userCredentials->getGallery()->getId());
        }else{
            $photos = null;
        }

        $socialPosts = $postRepository->loadNewPosts($userCredentials->getId());

        $posts = [];
        $postsIt=0;

        foreach ($socialPosts as $post){
            if($post->getSoftDelete()==false)            {
                $comments_length = $underCommentsRepo->countAllComments($post->getId());
                $main_comments_length = $underCommentsRepo->countMainComments($post->getId());
                $posts[$postsIt] = [
                    'Id'=>$post->getId(),
                    'Content'=>$post->getContent(),
                    'Author'=>$post->getAccount()->getId(),
                    'AuthorUsername'=>$post->getAccount()->getUsername(),
                    'AuthorFirstName'=>$post->getAccount()->getFirstName(),
                    'AuthorLastName'=>$post->getAccount()->getLastName(),
                    'AuthorAvatarFileName'=>$post->getAccount()->getAvatarFileName(),
                    'AuthorOccupation'=>$post->getAccount()->getOccupation(),
                    'createdAt'=>$post->getCreatedAt(),
                    'modifiedAt'=>$post->getModifiedAt(),
                    'Likes'=>$post->getLikes(),
                    'Comments'=>[],
                    'Comments_length'=>$comments_length[0]['1'],
                    'MainCommentsLength'=>$main_comments_length[0]['1'],
                    'BGFilename' => $post->getBackgroundFilename()
                ];
                $commentIt=0;
                $comments = $underCommentsRepo->findNewestComments($post->getId() ,'3');
                foreach($comments as $comment)
                {
                    if($comment->getSoftDelete()==false and $comment->getUnderAnotherComment() == null){
                    array_push($posts[$postsIt]['Comments'], [
                            'Id'=>$comment -> getId(),
                            'Content'=>$comment-> getContent(),
                            'Author'=>$comment-> getAuthor()->getId(),
                            'AuthorUsername'=>$comment->getAuthor()->getUsername(),
                            'AuthorFirstName'=>$comment->getAuthor()->getFirstName(),
                            'AuthorLastName'=>$comment->getAuthor()->getLastName(),
                            'AuthorAvatarFileName'=>$comment->getAuthor()->getAvatarFileName(),
                            'createdAt'=>$comment->getCreatedAt(),
                            'modifiedAt'=>$comment->getModifiedAt(),
                            'CommentConversation'=>[]
                        ]);

                    $underComments = $underCommentsRepo->findBy(['underAnotherComment'=>$comment->getId()]);
                    foreach ($underComments as $underComment) {
                        array_push($posts[$postsIt]['Comments'][$commentIt]['CommentConversation'], [
                                        'Id'=>$underComment -> getId(),
                                        'Content'=>$underComment-> getContent(),
                                        'Author'=>$underComment-> getAuthor()->getId(),
                                        'AuthorUsername'=>$underComment->getAuthor()->getUsername(),
                                        'AuthorFirstName'=>$underComment->getAuthor()->getFirstName(),
                                        'AuthorLastName'=>$underComment->getAuthor()->getLastName(),
                                        'AuthorAvatarFileName'=>$underComment->getAuthor()->getAvatarFileName(),
                                        'createdAt'=>$underComment->getCreatedAt(),
                                        'modifiedAt'=>$underComment->getModifiedAt()
                            ]);
                    }
                }
                $commentIt++;
                }
            $postsIt++;
            }
        }

        $contacts = $contactRepo->findContacts($userCredentials->getId());
        $contactProfile = [];

        $i = 0;
        foreach ($contacts as $contact){
            $contactProfile['fhd'.$i] = $accRepo->findJustUsernameAndAvatar($contact['contact']);//TODO: zrobic wyspecjalizowane wyszukiwanie bez zbednych informacji, sam username i avatar
            $i++;
        }
        //dd($contactProfile);

        $forum = $forumRepository->findOneBy(['UserAdmin'=>$userCredentials, 'softDelete'=>0]);

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/blank.html.twig",[
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile",
            'MainMenu' => $mainMenu,
            'profile'=>$userCredentials,
            'user'=>$user,
            'tab'=>$tab,
            'posts'=>$posts,
            'design'=>$design,
            'Contacts'=>$contactProfile,
            'gallery'=>$photos,
            'SOCIAL_POSTS'=>$_SERVER['SOCIAL_POSTS'],
            'theme'=>$this->theme,
            'forum'=>$forum,
            'comics'=>$comics
        ]);
    }

    /**
     * @Route("/api/uploadWallpaper/{user}", name="api-upload-wallpaper")
     * @Security("is_granted('ROLE_USER')")
     */
    public function uploadWallpaper(Request $request){
        $file  = $request->files->get('wallpaper');
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
     */
    public function addComment(Request $request, SocialPost $id, EntityManagerInterface $em){
        $token = $request->request->get('_token');

        if ($request->request->get('Comment_content') == ""){
            //TODO: validation when empty comment
        }

        if ($this->isCsrfTokenValid('comment', $token)){
            $newComment = new SocialPostComment();
            $newComment -> setAuthor($this->getUser());
            $newComment -> setContent($request->request->get('Comment_content'));
            $newComment-> setCreatedAt(new \DateTime('now'));
            $newComment ->setSoftDelete('0');
            $newComment ->setUnderAnotherComment(null);

            $id->addSocialPostComment($newComment);

            $em->persist($newComment);
            $em->persist($id);
            $em->flush();
        }else{
            //TODO: bad csrf
        }
        return $this->redirectToRoute('app_profile', ['profile'=>$request->request->get('profile_name')]);
    }


    /**
     * @Route("/addLike", name="app_addLike")
     * @Security("is_granted('ROLE_USER')")
     */
    public function likes(Request $request, SocialPostRepository $postRepository,EntityManagerInterface $em)
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('like', $token)){
            $id = $request->request->get('postId');

            $post = $postRepository->find($id);

            if ($post){

                $em->persist($post->setLikes($post->getLikes()+1));
                $em->flush();
            }else{
                //sory gowno lajkujesz cos czego nie ma
            }

        }
    }
}