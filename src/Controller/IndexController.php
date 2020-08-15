<?php


namespace App\Controller;


use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function homepage(MainMenuService $mainMenuService, AuthenticationUtils $authenticationUtils)
    {
        $mainMenu = $mainMenuService->getMenu();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/landing.page.twig",[
            'last_username'=>$lastUsername,
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