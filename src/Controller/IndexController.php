<?php


namespace App\Controller;


use App\Services\MainMenuInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Services\Kalk;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="landingpage")
     */
    public function homepage(Kalk $kalk, MainMenuInterface $mainMenu)
    {
        $someVariable = $mainMenu->getMenuJSON();

        return $this->render("blank.html.twig",[
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile - timeline"
        ]);
    }
}
/**
 * TODO: 1. nie dziala wybor kolorow w theme na twig
 */