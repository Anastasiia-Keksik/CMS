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
        return $this->render('/test/index.html.twig');
    }
}
