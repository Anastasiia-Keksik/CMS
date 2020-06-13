<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\UserPrivateForum;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumForumRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ForumController extends AbstractController
{


    /**
     * @Route("/forum", name="forum")
     */
    public function index(ForumCategoryRepository $forumCategoryRepository, MainMenuService $mainMenuService)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $forumCategoryRepository->findBy(['IsItUserPrivateForum'=>NULL]);

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/MainForumList.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/forum/MakeCategory/{userId}", name="forum_make_new_category")
     * @param null $userId
     * @param ForumCategoryRepository $categoryRepository
     *
     */
    public function makeNewCategory(Account $userId = NULL, ForumCategoryRepository $categoryRepository, MainMenuService $mainMenuService, UserInterface $user)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $categoryRepository->takeCategoriesByOrderValue(null);

        //UserId - przekazany ID z linku, $User - zalogowany user
        if($userId != NULL)
        {
            //check if this user is logged
            if ($userId->getUsername()==$user->getUsername())
            {
                $categories = $categoryRepository->takeCategoriesByOrderValue($userId->getId());
            }else{
                throw $this->createNotFoundException('Nie masz uprawnien do administracji forum tego użytkownika.');
            }
        }else{
            $categories = $categoryRepository->takeCategoriesByOrderValue(NULL);
        }

        //TODO zrobic rozne prawa dla roznych userow na prywatnych forumach


        // dump($user->getId);die;
        //dd($categories);
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewCategory.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/forum/MakeNewForum", name="forum_make_new_forum")
     *
     */
    public function makeNewForum(Account $userId = NULL, ForumCategoryRepository $categoryRepository, ForumForumRepository $forumRepository, MainMenuService $mainMenuService, UserInterface $user){
        $mainMenu = $mainMenuService->getMenu();
        //$categories = $categoryRepository->takeCategoriesByOrderValue(null);

        //UserId - przekazany ID z linku, $User - zalogowany user
        if($userId != NULL)
        {
            //check if this user is logged
            if ($userId->getUsername()==$user->getUsername())
            {
                $categories = $categoryRepository->takeCategoriesByOrderValue($userId->getId());
                $forums = $forumRepository->takeForumsByOrderValue();
            }else{
                return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewCategory.html.twig', [
                    'title'=>'Forum - '.$_SERVER['APP_NAME'],
                    'lang'=>'pl',
                    'APP_NAME'=>$_SERVER['APP_NAME'],
                    'logoSite'=>$_SERVER['SHOW_LOGO'],
                    'navFooter'=>$_SERVER['NAV_FOOTER'],
                    'footer'=>$_SERVER['FOOTER'],
                    'pageName'=>"Forum",
                    'MainMenu' => $mainMenu,
                ]);
            }
        }else{
            throw $this->createNotFoundException('Nie ma takiego uzytkownika');
        }

        //if($userId)

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewCategory.html.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
        ]);
    }


}
