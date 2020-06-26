<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AccountRepository;

class GalleryController extends AbstractController
{
    /**
     * @Route("/{username}/gallery", name="app_gallery")
     */
    public function index($username, AccountRepository $user)
    {
      $user = $user->findOneBy(['username'=>$username]);

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/gallery/index.html.twig', [
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Gallery",
            'user'=>$user
        ]);
    }
}
