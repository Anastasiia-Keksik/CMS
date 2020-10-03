<?php


namespace App\Controller;


use App\Repository\AccountRepository;
use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function homepage(MainMenuService $mainMenuService, AuthenticationUtils $authenticationUtils, Request $request)
    {
        $mainMenu = $mainMenuService->getMenu();



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $ip = $request->getClientIp();
        $ip = '93.105.160.50';

        $UserIpInformation = file_get_contents('http://api.ipapi.com/api/'.$ip.'?access_key=c45f51b9e846beab5b49e5b6184770c4');

        $countryCode = json_decode($UserIpInformation,  true)['country_code'];

        switch ($countryCode){
            case 'PL':
                $geoloc['country'] = 'Poland';
                $geoloc['currency'] = 'złotych';
                $price = 5;
                break;
            default:
                $geoloc['country'] = '???';
                $geoloc['currency'] = '$';
                $price = 5;
                break;
        }

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/landing.page.twig",[
            'last_username'=>$lastUsername,
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile",
            'MainMenu' => $mainMenu,
            'price' => $price,
            'geoloc'=>$geoloc,
        ]);
    }
}
/**
 * TODO: 1. nie dziala wybor kolorow w theme na twig
 * TODO: jesli zalogowany, przeslij gdzies indziej
 */