<?php

namespace App\Controller;

use App\Repository\GalleryAlbumRepository;
use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AccountRepository;

class GalleryController extends AbstractController
{
    /**
     * @Route("/{profile}/gallery", name="app_gallery")
     */
    public function galleryShow($profile, AccountRepository $user, MainMenuService $mainMenuService)
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
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>'
            ]);
        }
             return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/gallery/index.html.twig', [
                 'title'=>'title',
                 'lang'=>'pl',
                 'APP_NAME'=>$_SERVER['APP_NAME'],
                 'logoSite'=>$_SERVER['SHOW_LOGO'],
                 'navFooter'=>$_SERVER['NAV_FOOTER'],
                 'footer'=>$_SERVER['FOOTER'],
                 'pageName'=>"Gallery",
                 'profile'=>$profile,
                 'username'=>$username,
                 'albums'=>$album,
             ]);
        }

        /**
         * @Route("{profile}/gallery/album/{id}", name="app_gallery_album")
         */
        public function galleryAlbumShow($profile, $id, GalleryAlbumRepository $album, MainMenuService $mainMenuService)
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
                'album'=>$album
            ]);
        }
}
