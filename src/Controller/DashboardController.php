<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $theme;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])){

            $this->theme = $_COOKIE["theme"];

        }else{

            $this->theme = "#";

        }
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request)
    {
        return $this->render('smartadmin/dashboard/dashboard.html.twig', [
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Dashboard",
            'theme'=>$this->theme,
            'user'=>$this->getUser(),
            'profile'=>$this->getUser()
        ]);
    }
}
