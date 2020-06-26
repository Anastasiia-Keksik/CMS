<?php

namespace App\Controller;

use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumTopicRepository;
use App\Repository\UserForumPostRepository;
use App\Repository\UserForumTopicRepository;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="administration")
     */
    public function AdminPanelMain(MainMenuService $mainMenuService, ForumCategoryRepository $forumCategoryRepository, ForumPostRepository $forumPostRepository, ForumTopicRepository $forumTopicRepository, UserForumPostRepository $userForumPostRepository, UserForumTopicRepository $userForumTopicRepository)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $forumCategoryRepository->findBy(['IsItUserPrivateForum'=>NULL]);


        $lastPosts = $forumPostRepository->findLast10();
        $lastTopics = $forumTopicRepository->findLast10();
        $lastPrivatePosts = $userForumPostRepository->findLast10();
        $lastPrivateTopics = $userForumTopicRepository->findLast10();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/administration/administration.page.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Admin",
            'MainMenu' => $mainMenu,
            'categories'=>$categories,
            'lastPosts'=>$lastPosts,
            'lastTopics'=>$lastTopics,
            'lastPrivatePosts'=>$lastPrivatePosts,
            'lastPrivateTopics'=>$lastPrivateTopics

        ]);
    }
}
