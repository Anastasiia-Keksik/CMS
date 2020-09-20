<?php

namespace App\Controller;

use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class KomiksController extends AbstractController
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
     * @Route("/komiks", name="app_komiks")
     */
    public function index(MainMenuService $mainMenuService)
    {

        $user = $this->getUser();
        $profile = $user;

        $mainMenu = $mainMenuService->getMenu();

        return $this->render('comic/comicList.html.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $user,
            'NotComposed' => true,
        ]);
    }

    /**
     * @Route("/OmniViewer", name="omni_viewer")
     */
    public function omniViewer(MainMenuService $mainMenuService)
    {

        $user = $this->getUser();
        $profile = $user;

        $mainMenu = $mainMenuService->getMenu();

        return $this->render('comic/omniViewer.html.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $user,
            'NotComposed' => true,
        ]);
    }

}
