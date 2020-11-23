<?php

namespace App\Controller\Payment;

use App\Repository\AccountRepository;
use App\Repository\ComicRepository;
use App\Repository\ContactRepository;
use App\Repository\GalleryPhotosRepository;
use App\Repository\ProfileDesignSettingsRepository;
use App\Repository\SocialPostCommentRepository;
use App\Repository\SocialPostRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Payum\Core\Payum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PayPalController extends AbstractController
{
    private $theme;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])) {

            $this->theme = $_COOKIE["theme"];

        } else {

            $this->theme = "#";

        }
    }

    /**
     * @Route("/shineTheLight", name="show_prices")
     */
    public function priceList(Request $request, SocialPostCommentRepository $underCommentsRepo, MainMenuService $mainMenuService,
                              ProfileDesignSettingsRepository $pdsr, SocialPostRepository $postRepository, ContactRepository $contactRepo,
                              AccountRepository $accRepo, GalleryPhotosRepository $galleryPhotosRepository,
                              UserPrivateForumRepository $forumRepository, ComicRepository $comicsRepo, GetContactsService $getContactsService){
        $mainMenu = $mainMenuService->getMenu();

        $comics = $comicsRepo->findAll();
        $contacts = $getContactsService->getContacts($this->getUser()->getId());


        return $this->render("smartadmin/MoonLights/priceList.twig", [
            'title' => 'title',
            'lang' => 'pl',
            'APP_NAME' => $_SERVER['APP_NAME'],
            'logoSite' => $_SERVER['SHOW_LOGO'],
            'navFooter' => $_SERVER['NAV_FOOTER'],
            'footer' => $_SERVER['FOOTER'],
            'pageName' => "Profile",
            'MainMenu' => $mainMenu,
            'user' => $this->getUser(),
            'profile' => $this->getUser(),
            'SOCIAL_POSTS' => $_SERVER['SOCIAL_POSTS'],
            'comics' => $comics,
            'theme' => $this->theme,
            'Contacts' => $contacts
        ]);
    }
}