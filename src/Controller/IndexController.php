<?php


namespace App\Controller;


use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="landingpage")
     */
    public function homepage(MainMenuService $mainMenuService)
    {
        $mainMenu = $mainMenuService->getMenu();

        dump ($mainMenu);
        //$MainMenuChildren = $mainMenu->getMainMenuChildren();

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/blank.html.twig",[
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile",
            'MainMenu' => $mainMenu,

        ]);
    }
}
/**
 * TODO: 1. nie dziala wybor kolorow w theme na twig
 * TODO: jesli zalogowany, przeslij gdzies indziej
 */