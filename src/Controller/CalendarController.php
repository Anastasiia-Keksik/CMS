<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\SocialPostCommentRepository;
use App\Repository\SocialPostRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CalendarController extends AbstractController
{
    private $theme;

    private $contacts;

    private $comics;

    private $user;

    public function __construct(GetContactsService $contactsRepository)
    {
        if (isset($_COOKIE["theme"])){

            $this->theme = htmlspecialchars($_COOKIE["theme"]);

        }else{

            $this->theme = "#";

        }

        $this->contacts = $contactsRepository->getContacts($this->user);
        $this->comics = $contactsRepository->getComics($this->user);
    }

    /**
     * @Route("/calendar/{user}/PostsAndComments", name="calendarPostsAndComments")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showCalendarPosts($user, AccountRepository $userRepo, MainMenuService $mainMenu)
    {

        $this->user = $userRepo->findOneBy(['username'=>$user]);

        if ($this->user === $this->getUser()){


            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/calendar/calendarPostsAndComments.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu->getMenu(),
                'profile'=>$this->user,
                'user'=>$this->user,
                'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
                'theme'=>$this->theme,
                'Contacts' => $this->contacts,
                'comics' => $this->comics
            ]);
        }else{
            die('You are not allowed to see that page');
        }
    }

    /**
     * @Route("/calendar/{user}/ComicsRead", name="calendarComicsRead")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showCalendarComicRead($user, AccountRepository $userRepo, MainMenuService $mainMenu)
    {

        $this->user = $userRepo->findOneBy(['username'=>$user]);

        if ($this->user === $this->getUser()){


            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/calendar/calendarCreationViewed.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu->getMenu(),
                'profile'=>$this->user,
                'user'=>$this->user,
                'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
                'theme'=>$this->theme,
                'Contacts' => $this->contacts,
                'comics' => $this->comics
            ]);
        }else{
            die('You are not allowed to see that page');
        }
    }

    /**
     * @Route("/calendar/{user}/ComicsSub", name="calendarComicsSub")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showCalendarComicSub($user, AccountRepository $userRepo, MainMenuService $mainMenu)
    {

        $this->user = $userRepo->findOneBy(['username'=>$user]);

        if ($this->user === $this->getUser()){


            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/calendar/calendarCreationSub.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu->getMenu(),
                'profile'=>$this->user,
                'user'=>$this->user,
                'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
                'theme'=>$this->theme,
                'Contacts' => $this->contacts,
                'comics' => $this->comics
            ]);
        }else{
            die('You are not allowed to see that page');
        }
    }

    /**
     * @Route("/calendar/{user}/Followed", name="calendarFollowed")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showCalendarFollowed($user, AccountRepository $userRepo, MainMenuService $mainMenu)
    {

        $this->user = $userRepo->findOneBy(['username'=>$user]);

        if ($this->user === $this->getUser()){


            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/calendar/calendarUsersFollowed.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu->getMenu(),
                'profile'=>$this->user,
                'user'=>$this->user,
                'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
                'theme'=>$this->theme,
                'Contacts' => $this->contacts,
                'comics' => $this->comics
            ]);
        }else{
            die('You are not allowed to see that page');
        }
    }


    /**
     * @Route("/getCalendarData", name="calendar_fetchData")
     * @Security("is_granted('ROLE_USER')")
     */
    public function fetchCalendarData(Request $request, SocialPostRepository $socialPostRepository, SocialPostCommentRepository $socialPostCommentRepository, SerializerInterface $serializer)
    {
        $start = $request->request->get('start');
        $end = $request->request->get('end');

        $posts = $socialPostRepository->calendarGet($this->getUser()->getId(), $start, $end);

        $response =[];
        $i = 0;
        foreach ($posts as $post){
            $date = $serializer->serialize($post->getCreatedAt()->format('Y-m-d\TH:m:s'), 'json');
            $response[$i] = ['title'=>substr(strip_tags($post->getContent()), 0, 32), 'start'=>substr($date, 1, strlen($date)-2), 'className'=>'bg-success border-success text-white'];
            $i++;
        }

        $comments = $socialPostCommentRepository->calendarGet($this->getUser()->getId(), $start, $end);

        foreach ($comments as $comment){
            $date = $serializer->serialize($comment->getCreatedAt()->format('Y-m-d\TH:m:s'), 'json');
            $response[$i] = ['title'=>substr(strip_tags($comment->getContent()), 0, 32), 'start'=>substr($date, 1, strlen($date)-2), 'className'=>'bg-info border-info text-white'];
            $i++;
        }

        return new JsonResponse($response);

    }
}
