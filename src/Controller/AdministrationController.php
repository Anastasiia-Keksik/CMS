<?php

namespace App\Controller;

use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="administration")
     */
    public function AdminPanelMain(MainMenuService $mainMenuService)
    {
        $mainMenu = $mainMenuService->getMenu();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/administration/administration.page.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Admin",
            'MainMenu' => $mainMenu,

        ]);
    }
}
