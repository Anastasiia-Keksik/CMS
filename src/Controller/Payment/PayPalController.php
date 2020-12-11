<?php

namespace App\Controller\Payment;

use App\Entity\Account;
use App\StripeClient;
use App\Repository\ComicRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("/StripeAcc", name="payment_dashboard")
     */
    public function paymentDashboard(Request $request, MainMenuService $mainMenuService,
                                     ComicRepository $comicsRepo, GetContactsService $getContactsService){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $mainMenu = $mainMenuService->getMenu();

        /** @var Account $user */
        $user = $this->getUser();

        $comics = $comicsRepo->findAll();
        $contacts = $getContactsService->getContacts($this->getUser()->getId());

        $stripe = new \Stripe\StripeClient(
            $_SERVER['stripe_secret_key']
        );

        $account = $stripe->accounts->retrieve($user->getStripeAccountId());

        $extAcc = $stripe->accounts->allExternalAccounts(
            $account->id,
            ['object' => 'card', 'limit' => 99]
        );

        $accountShow = false;
        if ($request->get('mode')=='show'){
            $accountShow = true;

            $link = $stripe->accountLinks->create([
                'account' => $user->getStripeAccountId(),
                'refresh_url' => 'https://127.0.0.1:8000/profile',
                'return_url' => 'https://127.0.0.1:8000/profile',
                'type' => 'account_onboarding',]);

            return new RedirectResponse($link->url);
        }

        dump($account);
        dump($extAcc);




        return $this->render("smartadmin/dashboard/stripeDashboard.twig", [
            'title' => 'Stripe Account',
            'lang' => 'pl',
            'APP_NAME' => $_SERVER['APP_NAME'],
            'logoSite' => $_SERVER['SHOW_LOGO'],
            'navFooter' => $_SERVER['NAV_FOOTER'],
            'footer' => $_SERVER['FOOTER'],
            'pageName' => "Stripe Account",
            'MainMenu' => $mainMenu,
            'user' => $this->getUser(),
            'profile' => $this->getUser(),
            'SOCIAL_POSTS' => $_SERVER['SOCIAL_POSTS'],
            'comics' => $comics,
            'theme' => $this->theme,
            'Contacts' => $contacts,
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'accountShow' => $accountShow,
            'account' => $account,
            'externalAccounts'=>$extAcc
        ]);
    }

    /**
     * @Route("/createExternalAccount", name="App_Create_External_Account")
     */
    public function createStripeExternalAccount(Request $request, EntityManagerInterface $em)
    {
        $stripe = new \Stripe\StripeClient(
            $_SERVER['stripe_secret_key']
        );

        /** @var Account $user */
        $user = $this->getUser();

        $post = $request->request->all();



        //dd($post);
        if ($post['routing_number']){

        } else if ($post['sort_number']){

        }else{

        }

        $stripe->accounts->createExternalAccount(
            $user->getStripeAccountId(),
            [
                'external_account' => [
                    'object' => 'bank_account',
                    'country' => $post['country'],
                    'currency' => $post['currency'],
                    'account_holder_name' =>$post['account_holder_name'], // optional
                    'account_number' => $post['acc_number']
                ]
            ]
        );
    }

    /**
     * @Route("/createStripeAccount", name="App_Create_Stripe_Account")
     */
    public function createStripeAccount(Request $request, EntityManagerInterface $em){
        $stripe = new \Stripe\StripeClient(
            $_SERVER['stripe_secret_key']
        );

        /** @var Account $user */
        $user = $this->getUser();

        $country = $request->request->get('select-region');

        if (!$user->getStripeAccountPending()){
            $account = $stripe->accounts->create([
                'type' => 'standard',
                'country' => $country,
                'email' => $user->getEmail(),
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
            $account->save();

            //die();
            /**
             * @var UploadedFile $fileFront
             * @var UploadedFile $fileBack
             * @var UploadedFile $fileAdditionalFront
             */
            $fileFront = $request->files->get('file-front');
            $ext = $fileFront->guessExtension();
            $fileFront -> move($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/", "IDfront.".$ext);
            $fileFronth = fopen($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/IDfront.".$ext, 'r');
            $fileFront = $stripe->files->create([
                'purpose' => 'identity_document',
                'file' => $fileFronth
            ]);
            fclose($fileFronth);
            $fileBack = $request->files->get('file-back');
            $ext = $fileBack->guessExtension();
            $fileBack -> move($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/", "IDback.".$ext);
            $fileBackh = fopen($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/IDback.".$ext, 'r');
            $fileBack = $stripe->files->create([
                'purpose' => 'identity_document',
                'file' => $fileBackh
            ]);
            fclose($fileBackh);
            $fileAdditionalFront = $request->files->get('address-file-front');
            $ext = $fileAdditionalFront->guessExtension();
            $fileAdditionalFront -> move($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/", "IDadditionalFront.".$ext);
            $fileAdditionalFronth = fopen($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/IDadditionalFront.".$ext, 'r');
            $fileAdditionalFront = $stripe->files->create([
                'purpose' => 'identity_document',
                'file' => $fileAdditionalFronth
            ]);
            fclose($fileAdditionalFronth);
            if ($request->files->get('address-file-back')) {
                $fileAdditionalBack = $request->files->get('address-file-back');
                $ext = $fileAdditionalBack->guessExtension();
                $fileAdditionalBack -> move($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/", "IDadditionalBack.".$ext);
                $fileAdditionalBackh = fopen($this->getParameter('kernel.project_dir') . "/public/upload/profile/".$user->getUsername()."/IDadditionalBack.".$ext, 'r');
                $fileAdditionalBack = $stripe->files->create([
                    'purpose' => 'identity_document',
                    'file' => $fileAdditionalBackh
                ]);
                fclose($fileAdditionalBackh);
            }else{
                $fileAdditionalBack = null;
            }

            $stripe->accounts->update(
                $account->id,
                [
                    'tos_acceptance' => [
                        'date' => time(),
                        'ip' => $_SERVER['REMOTE_ADDR'], // Assumes you're not using a proxy
                    ],
                    'individual' => [
                        'dob' => [
                            'day' => $user->getBday(),
                            'month' => $user->getBmonth(),
                            'year' => $user->getByear()
                        ],
                        'address' => [
                            'city' => $user->getCity(),
                            'line1' => $user->getLine1(),
                            'line2' => $user->getLine2(),
                            'postal_code' => $user->getPostalCode(),
                            'state' => $user->getPostalCode()
                        ],
                        'phone' => $user->getPhone(),
//                        'verification' => [
//                            'additional_document' => [
//                                'front'=>$fileAdditionalFront->id,
//                                'back'=>$fileAdditionalBack->id
//                                ],
//                            'document' => [
//                                'front' =>$fileFront->id,
//                                'back' =>$fileBack->id
//                            ]
//                        ],
                        'email' => $user->getEmail(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                    ],
                    'business_profile' => [
                        'mcc' => '7333',
                        'url' => $this->generateUrl('app_profile',  array('profile' => $user->getUsername()), UrlGeneratorInterface::ABSOLUTE_URL),
                        'product_description' => "Art - comics, books and translations - graphics art related."
                    ],
                    'business_type' => 'individual'
                ]
            );
            dump($account);
            //die();

            $user->setStripeAccountPending(1);
            $user->setStripeAccountId($account->id);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Account Created. Please wait couple minutes until Stripe will activate it.");

            return new RedirectResponse($this->generateUrl('payment_dashboard', ['mode'=>'show']));
        }

        $this->addFlash('success', "You already created account, please wait until it will be accepted by Stripe.");

        return new RedirectResponse($this->generateUrl('payment_dashboard', ['mode'=>'show']));
    }

    /**
     * @Route("/payOut", name="app_funds_payout")
     * @Security("is_granted('ROLE_USER')")
     */
    public function fundsPayout(){

        /** @var Account $user */
        $user = $this->getUser();
        $lightsPayoutAmount = 500;
        $payoutAmount = 500; //przeliczone juz na zlotowki

        $stripe = new \Stripe\StripeClient(
            $_SERVER['stripe_secret_key']
        );

        $payout = $stripe->payouts->create([
            'amount' => $payoutAmount,
            'currency' => 'pln',
            'description' => 'Wypłada '.$lightsPayoutAmount.' MoonLightów.',
            'destination' => '05 1240 2265 1111 0010 4518 9958',
            'statement_descriptor' => 'Payout to You from MoonLight'
        ]);

        dump($payout);

        die();

        return new RedirectResponse($this->generateUrl('app_main_profile'));
    }

    /**
     * @Route("/shineTheLight", name="show_prices", schemes={"%secure_channel%"})
     */
    public function priceList(Request $request, MainMenuService $mainMenuService,
                              ComicRepository $comicsRepo, GetContactsService $getContactsService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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
            'Contacts' => $contacts,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ]);
    }

    /**
     * @Route("/checkoutFormCatch", name="catch_card_token", schemes={"%secure_channel%"})
     */
    public function catchCardToken(Request $request, StripeClient $stripeClient)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $error = false;
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            $lights = $request->request->get('lights');
            $currency = 'pln';

            switch ($currency) {
                case 'pln':
                    $price = $lights * 1;
                    break;
            }

            /** @var Account $user */
            $user = $this->getUser();

            try{
                $this->chargeCustomer($user, $stripeClient, $token, $request);
            } catch(\Stripe\Exception\CardException $e) {
                $error = 1;
                $this->addFlash('error', 'There was a problem charging the card. ' . $e->getMessage() . ' Please try again or use another card.');
            } catch (\Stripe\Exception\RateLimitException $e) {
                $error = 1;
                $this->addFlash('error', 'Nastąpiło zbyt wiele połączeń na raz. Spróbuj ponownie.');
                // Too many requests made to the API too quickly
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $error = 1;
                $this->addFlash('error', 'Błąd wewnętrzyny - zostały podane błędne parametry do Stripe\a');
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Exception\AuthenticationException $e) {
                $error = 1;
                $this->addFlash('error', 'Authentication with Stripe\'s API failed');
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                $error = 1;
                $this->addFlash('error', 'Network communication with Stripe failed');
                // Network communication with Stripe failed
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $error = 1;
                $this->addFlash('error', 'Błąd przetwarzania karty, spróbuj ponownie.');
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e) {
                $error = 1;
                $this->addFlash('error', 'Nieznany błąd przetwarzania karty, spróbuj ponownie.');
                // Something else happened, completely unrelated to Stripe
            }


            if (!$error)
            {
                $request->request->get('shopping_cart');
                $this->addFlash('success', 'Order Complete!');

                return new RedirectResponse($this->generateUrl('app_main_profile'));
            }
        }
        return new RedirectResponse($this->generateUrl('show_prices'));
    }

    /**
     * @param Account $user
     * @param StripeClient $stripeClient
     * @param string $token
     * @param Request $request
     */
    public function chargeCustomer(Account $user, StripeClient $stripeClient, ?string $token, Request $request): void
    {
        if (!$user->getStripeCustomerId()) {
            $stripeClient->createCustomer($user, $token);
        } else {
            $stripeClient->updateCustomerCard($user, $token);
        }

        for ($i = 1; $i <= $request->get('items-count'); $i++) {
            $stripeClient->createInvoiceItem(
                $request->get('item-' . $i),
                $user,
                'pln',
                "Shine " . $request->get('item-' . $i) . " lights on " . $user->getEmail()
            );
        }

        $stripeClient->createInvoice($user, true);
    }
}