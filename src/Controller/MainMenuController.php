<?php

namespace App\Controller;

use App\Entity\MainMenuCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainMenuController extends AbstractController
{
    /**
     * @Route("/main/menu", name="main_menu")
     */
    public function index(MainMenuCategory $mainMenuCategory)
    {
        dd($mainMenuCategory);
    }
}
