<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumForumRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserForumController extends AbstractController
{

    /**
     * @Route("/{profile}/forum", name="user_forum")
     */
    public function user_forum(string $profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService, UserPrivateForumRepository $forumRepository)
    {
        $mainMenu = $mainMenuService->getMenu();
        $userRepository = $entityManager->getRepository(Account::class);
        //$forumRepository = $entityManager->getRepository(UserPrivateForumRepository::class);

        $user = $this->getUser();
        if($user)
        {
            $username = $user->getUsername();
        }else{
            $username = NULL;
        }

        $userCredentials = $userRepository->findOneBy(['username' => $profile]);

        if (!$userCredentials)
        {
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/404.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
                'error_msg' => 'There is no username with this name.',
                'middle_error_msg' => '',
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
            ]);
        }

        $forum = $forumRepository->findOneBy(['UserAdmin'=>$userCredentials->getId()]);

        if (!$forum)
        {
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/404.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
                'error_msg' => 'This username does not have forum',
                'middle_error_msg' => '',
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
            ]);
        }
        //dd($forum);
//        $array1 = $userForumPost ->findAll();
//        $array2 = $forumPost ->findAll();
//
//        $posts = array_merge($array1, $array2);
//
//        usort($posts, function ($array1, $array2) {     return strcmp(date_format($array1->getCreatedAt(), 'U'), date_format($array2->getCreatedAt(), 'U')); });
//
//        dd($posts);


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
            'loggedUserUsername' => $username
        ]);
    }

    //TODO zamienic userId na UserName aby w linku byl username a nie jakas cyferka
    /**
     * @Route("{userId}/forum/MakeCategory/", name="forum_make_new_category_user")
     * @param null $userId
     * @param ForumCategoryRepository $categoryRepository
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewCategoryForUser(Account $userId = NULL, ForumCategoryRepository $categoryRepository, MainMenuService $mainMenuService, UserInterface $user)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $categoryRepository->takeCategoriesByOrderValue(null);

        //check if this logged user == slug user id
        if ($userId->getUsername()==$user->getUsername())
        {
            //when user logged == user from slug id then take category hierarchy
            $categories = $categoryRepository->takeCategoriesByOrderValue($userId->getId());
        }else{
            //show 403 inside page when logged user is not the one in the link slug id
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/403.inside.error.page.twig', [
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
     * @Route("{user}/forum/MakeNewForum/", name="forum_make_new_forum_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewForumForUser(Account $userId = NULL, ForumCategoryRepository $categoryRepository, ForumForumRepository $forumRepository, MainMenuService $mainMenuService, UserInterface $user){
        $mainMenu = $mainMenuService->getMenu();
        //$categories = $categoryRepository->takeCategoriesByOrderValue(null);

        //Check if user id == logged user
        if ($userId->getUsername()==$user->getUsername())
        { //take user forum categories and forum hierarchy
            $categories = $categoryRepository->takeCategoriesByOrderValue($userId->getId());
            $forums = $forumRepository->takeForumsByOrderValue();
        }else{
            //show 403 inside page when logged user is not the one in the link slug id
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/403.inside.error.page.twig', [
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
