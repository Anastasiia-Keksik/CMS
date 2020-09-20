<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GlobalSettingsController extends AbstractController
{
    /**
     * @Route("/make/cookie/theme/{nr}", name="global_settings_theme_cookie")
     */
    public function makeCookieThemeNr($nr, Request $request)
    {
        $res = new Response();
        $cookie = new Cookie(
            "theme",
            "/smartadmin/css/themes/cust-theme-$nr.css",
            time() + ( 365 * 24 * 60 * 60)  // Expires 2 years.
        );
        $res->headers->setCookie( $cookie );
        $res->send();

        $refurl = $request->headers->get('referer');

        if($refurl == null){
            $refurl = '/PaYo1/forum';
        }

        return new RedirectResponse($refurl);
    }
}
