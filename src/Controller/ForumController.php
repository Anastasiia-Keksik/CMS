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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted('ROLE_USER')
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
     * @Route("/{profile}/forum", name="user_forum")
     */
    public function user_forum(string $profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService, UserPrivateForumRepository $forumRepository, UserInterface $user)
    {
        $mainMenu = $mainMenuService->getMenu();
        $userRepository = $entityManager->getRepository(Account::class);
        //$forumRepository = $entityManager->getRepository(UserPrivateForumRepository::class);

        $userCredentials = $userRepository->findOneBy(['username' => $profile]);

        if (!$userCredentials)
        {
            throw $this->createNotFoundException('Nie znaleziono takiego uzytkownika');
        }

        $forum = $forumRepository->findOneBy(['UserAdmin'=>$userCredentials->getId()]);

        if (!$forum)
        {
            throw $this->createNotFoundException('Ten urzytkownik nie posiada forum.');
        }
        //dd($forum);

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/ProfileForumList.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'forum'=>$forum,
            'thisForumUserAdmin'=>$userCredentials,
            'loggedUserUsername' => $user->getUsername()
        ]);
    }

    /**
     * @Route(/forum/MakeNewForum/{userId}, name="forum_make_new_forum")
     * @IsGranted('ROLE_USER')
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
                throw $this->createNotFoundException('Nie masz uprawnien do administracji forum tego użytkownika.');
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
