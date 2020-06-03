<?php


namespace App\Controller;

use App\Entity\Account;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_main_profile")
     */
    public function viewMineProfile(MainMenuService $mainMenuService)
    {
        $mainMenu = $mainMenuService->getMenu();
        /** @var Account $profile */
        $profile = $this->getUser();
       // $profile -> getUsername();

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/blank.html.twig",[
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile",
            'MainMenu' => $mainMenu,
            'profile'=>$profile
        ]);
    }

    /**
     * @Route("/profile/{profile}", name="app_profile")
     */
    public function viewProfile($profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService)
    {
        $mainMenu = $mainMenuService->getMenu();
        $repository = $entityManager->getRepository(Account::class);

        //$userCredentials = $repository->takeUser($profile);
        $userCredentials = $repository->findOneBy(['username' => $profile]);

        if (!$userCredentials)
        {
            throw $this->createNotFoundException('Nie znaleziono takiego uzytkownika');
        }

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
        ]);
    }
}