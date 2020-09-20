<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\GalleryAlbum;
use App\Repository\GalleryAlbumRepository;
use App\Repository\GalleryPhotosRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GalleryController extends AbstractController
{

    private $theme;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])){

            $this->theme = htmlspecialchars($_COOKIE["theme"]);

        }else{

            $this->theme = "#";

        }
    }

    /**
     * @Route("/{profile}/gallery", name="app_gallery")
     */
    public function galleryShow($profile, AccountRepository $user, MainMenuService $mainMenuService,
                                GalleryPhotosRepository $galleryPhotosRepository, PaginatorInterface $paginator,
                                Request $request, SessionInterface $session)
    {
        $zalogowanyUser = $this->getUser();
        if ($zalogowanyUser)
        {
            $username = $zalogowanyUser->getUsername();
        }else{
            $username = '';
        }
        $mainMenu = $mainMenuService->getMenu();
        $profile = $user->findOneBy(['username'=>$profile]);
        $gallery = $profile->getGallery();
        $query = $galleryPhotosRepository->takePhotos($gallery->getId());

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            100 /*limit per page*/
        );
        if($gallery)
        {
            $album=$gallery->getGalleryAlbums();
        }else{
            $album=NULL;
        }
        if(!$user)
        {
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/404.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Gallery",
                'MainMenu' => $mainMenu,
                'error_msg' => 'This user does not exist.',
                'middle_error_msg' => '',
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
                'theme'=>$this->theme
            ]);
        }
             return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/gallery/gallery_main.twig', [
                 'title'=>'title',
                 'lang'=>'pl',
                 'APP_NAME'=>$_SERVER['APP_NAME'],
                 'logoSite'=>$_SERVER['SHOW_LOGO'],
                 'navFooter'=>$_SERVER['NAV_FOOTER'],
                 'footer'=>$_SERVER['FOOTER'],
                 'pageName'=>"Gallery",
                 'profile'=>$profile,
                 'user'=>$this->getUser(),
                 'username'=>$username,
                 'albums'=>$album,
                 'gallery'=>$pagination,
                 'theme'=>$this->theme
             ]);
        }

        /**
         * @Route("{profile}/gallery/album/{id}", name="app_gallery_album")
         */
        public function galleryAlbumShow($profile, $id, GalleryAlbumRepository $album, AccountRepository $user,
                                         MainMenuService $mainMenuService, GalleryPhotosRepository $galleryPhotosRepository,
                                         PaginatorInterface $paginator, Request $request)
        {
            $zalogowanyUser = $this->getUser();
            if ($zalogowanyUser)
            {
                $username = $zalogowanyUser->getUsername();
            }else{
                $username = '';
            }
            $mainMenu = $mainMenuService->getMenu();

            $album = $album->find($id);

            $profile = $user->findOneBy(['username'=>$profile]);

            $query = $galleryPhotosRepository->takeAlbumPhotos($album->getId());

            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                100 /*limit per page*/
            );

            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/gallery/album.twig', [
                'title'=>'title',
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Gallery",
                'profile'=>$profile,
                'username'=>$username,
                'album'=>$album,
                'user'=>$zalogowanyUser,
                'zdjecia'=>$pagination,
                'theme'=>$this->theme
            ]);
        }

        /**
         * @Route("/gallery/makeAlbum", name="gallery_make_album")
         * @Security("is_granted('ROLE_USER')")
         */
        public function makeAlbum(EntityManagerInterface $em, Request $request)
        {
            $gallery = new GalleryAlbum();
            $gallery->setName($request->request->get('title'));
            $gallery->setDescription($request->request->get('desc'));
            $gallery->setCreatedAt(new \DateTime());
            $gallery->setGallery($this->getUser()->getGallery());
            $gallery->setPhotos(0);

            $em->persist($gallery);
            $em->flush();

            return new RedirectResponse($this->generateUrl('app_gallery', ['profile'=>$this->getUser()->getUsername()]));
        }

        /**
         * @Route("api/updateAlbum/{album}", name="update_Album")
         * @Security("is_granted('ROLE_USER')")
         */
        public function updateAlbum(EntityManagerInterface $em, Request $request)
        {
            

            return new RedirectResponse($this->generateUrl('app_gallery', ['profile'=>$this->getUser()->getUsername()]));
        }
}
