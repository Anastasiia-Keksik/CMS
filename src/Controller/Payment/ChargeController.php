<?php

namespace App\Controller\Payment;

use App\Repository\ComicRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Payment\Payment;

class ChargeController extends AbstractController
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
     * @Route("/charge", name="show_prices_charge")
     */
    public function priceList(Request $request, MainMenuService $mainMenuService,
                              ComicRepository $comicsRepo, GetContactsService $getContactsService){
        $mainMenu = $mainMenuService->getMenu();

        $comics = $comicsRepo->findAll();
        $contacts = $getContactsService->getContacts($this->getUser()->getId());

        $pay = new Payment();
        $card['card'] = '4242424242424242';
        $card['expiremonth'] = '12';
        $card['expireyear'] = '2020';
        $card['cvv'] = '123';

        $this->createRequest('\Omnipay\Stripe\Message\CreateTokenRequest', $parameters);



        $check = $pay->setcard($card);

        if($check === true){
            $amount['amount'] = "100.00";
            $amount['currency'] = "USD";
            echo $pay->makepayment($amount);
        }else{
            echo $check;
        }

        die();

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