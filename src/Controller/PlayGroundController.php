<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Repository\ComicRepository;
use App\Services\MainMenuService;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;

class PlayGroundController extends AbstractController
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
     * @Route("/playground", name="playground")
     */
    public function index(MainMenuService $mainMenuService, ComicRepository $comicsRepo)
    {
        $comics = $comicsRepo->findAll();
        $username = $this->getUser()->getUsername();
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken(
                new Sha256(),
                new Key($this->getParameter('mercure_secret_key'))
            )
        ;


        $user = $this->getUser();
        $mainMenu = $mainMenuService->getMenu();

        $response = $this->render('playground/index.html.twig', [
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
        'profile'=>$user,
        'user'=>$user,
        'theme'=>$this->theme,
        'comics'=>$comics
    ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                    ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );

        return $response;
//
//        $user = $this->getUser();
//        $mainMenu = $mainMenuService->getMenu();
//        return $this->render('playground/index.html.twig', [
//            'title'=>'Forum - '.$_SERVER['APP_NAME'],
//            'lang'=>'pl',
//            'APP_NAME'=>$_SERVER['APP_NAME'],
//            'logoSite'=>$_SERVER['SHOW_LOGO'],
//            'navFooter'=>$_SERVER['NAV_FOOTER'],
//            'footer'=>$_SERVER['FOOTER'],
//            'pageName'=>"Forum",
//            'MainMenu' => $mainMenu,
//            'error_msg' => 'There is no username with this name.',
//            'middle_error_msg' => '',
//            'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
//            'profile'=>$user,
//            'theme'=>$this->theme
//        ]);
    }
}
