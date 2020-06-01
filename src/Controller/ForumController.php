<?php

namespace App\Controller;

use App\Entity\ForumForum;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{


    /**
     * @Route("/forum", name="app_forum")
     */
    public function index(ForumCategoryRepository $forumRepository, ForumPostRepository $postRepository)
    {
        $categories = $forumRepository->findBy(['IsItUserPrivateForum'=>NULL]);

//        foreach ($categories as $category){
//            dump($category);
//            $forum = $category->getForumForum()->last();
//            dump($forum);
//            /** @var ForumForum $forum  */
//            $topic = $forum->getForumTopics()->last();
//            dump($topic);
//        }
//        die;

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/list.html.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'categories'=>$categories
        ]);
    }
}
