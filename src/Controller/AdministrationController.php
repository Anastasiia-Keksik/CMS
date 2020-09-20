<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\MainMenuCategory;
use App\Form\ChoseMenuCategoryFormType;
use App\Form\MakeNewMenuCategoryFormType;
use App\Form\MakeNewRouteFormType;
use App\Repository\MainMenuCategoryRepository;
use App\Repository\MainMenuChildRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumPostRepository;
use App\Repository\ForumTopicRepository;
use App\Repository\UserForumPostRepository;
use App\Repository\UserForumTopicRepository;

/**
 * Class AdministrationController
 * @package App\Controller
 */
class AdministrationController extends AbstractController
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
     * @Route("/administration", name="administration")
     * @IsGranted("ROLE_ADMIN")
     */
    public function AdminPanelMain(MainMenuService $mainMenuService, EntityManagerInterface $em,
                                   ForumCategoryRepository $forumCategoryRepository,
                                   ForumPostRepository $forumPostRepository,
                                   ForumTopicRepository $forumTopicRepository,
                                   UserForumPostRepository $userForumPostRepository,
                                   UserForumTopicRepository $userForumTopicRepository,
                                   Request $request, MainMenuChildRepository $childRepository)
    {
        if($request->query->get('routeid')){
            $object = $childRepository->find($request->query->get('routeid'));
        }else{
        }

        $mainMenu = $mainMenuService->getMenu();
        $mainMenuForAdmin = $mainMenuService->getMenuForAdmin();

        $categories = $forumCategoryRepository->findBy(['IsItUserPrivateForum'=>NULL]);

        $lastPosts = $forumPostRepository->findLast10();
        $lastTopics = $forumTopicRepository->findLast10();
        $lastPrivatePosts = $userForumPostRepository->findLast10();
        $lastPrivateTopics = $userForumTopicRepository->findLast10();

        if ($request->request->get('category')){
            $cat = new MainMenuCategory();
            $cat->setName();
            $cat->setHidden();
            $cat->setName();
            $cat->setName();


//            $em->persist($);
//            $em->flush();

            return $this->redirectToRoute('administration');
        }

        if ($request->request->get('route')){

//            $em->persist($);
//            $em->flush();

            return $this->redirectToRoute('administration');
        }
        $user = $this->getUser();


        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/administration/administration.page.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Admin",
            'MainMenu' => $mainMenu,
            'MainMenuForAdmin' => $mainMenuForAdmin,
            'categories'=>$categories,
            'lastPosts'=>$lastPosts,
            'lastTopics'=>$lastTopics,
            'lastPrivatePosts'=>$lastPrivatePosts,
            'lastPrivateTopics'=>$lastPrivateTopics,
            'something'=>null,
            'user' => $user,
            'theme'=>$this->theme
        ]);
    }

//    /**
//     * @param Account $account
//     * @param EntityManagerInterface $em
//     * @Route("setAdminFor/{account}")
//     */
//    public function setAdmin(Account $account, EntityManagerInterface $em)
//    {
//        $account->setRoles(['ROLE_ADMIN']);
//
//        $em->flush();
//
//        echo "zmieniles";
//
//    }
}
