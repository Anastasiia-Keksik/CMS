<?php


namespace App\Controller;


use App\Entity\Account;
use App\Entity\Circles;
use App\Entity\InvitationRequest;
use App\Repository\AccountRepository;
use App\Repository\CirclesRepository;
use App\Repository\ContactRepository;
use App\Repository\InvitationRequestRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function homepage(MainMenuService $mainMenuService, AuthenticationUtils $authenticationUtils, Request $request)
    {
        $mainMenu = $mainMenuService->getMenu();

        if ($this->isGranted("ROLE_USER") == true) {
            return new RedirectResponse($this->generateUrl('app_profile', ['profile' => $this->getUser()->getUsername()]));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $ip = $request->getClientIp();
        $ip = '93.105.160.50';

        $UserIpInformation = file_get_contents('http://api.ipapi.com/api/' . $ip . '?access_key=c45f51b9e846beab5b49e5b6184770c4');

        $countryCode = json_decode($UserIpInformation, true)['country_code'];

        switch ($countryCode) {
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

        return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/landing.page.twig", [
            'last_username' => $lastUsername,
            'title' => 'title',
            'lang' => 'pl',
            'APP_NAME' => $_SERVER['APP_NAME'],
            'logoSite' => $_SERVER['SHOW_LOGO'],
            'navFooter' => $_SERVER['NAV_FOOTER'],
            'footer' => $_SERVER['FOOTER'],
            'pageName' => "Profile",
            'MainMenu' => $mainMenu,
            'price' => $price,
            'geoloc' => $geoloc,
        ]);
    }

    /**
     * @Route("/searchForContact", name="app_searchForContact")
     * Security("is_granted('ROLE_USER')")
     */
    public function searchForContact(Request $request, AccountRepository $accountRepository)
    {
        $contacts = $accountRepository->searchByName($request->request->get("string"), $this->getUser()->getId());
        //dd($contacts);
        return new JsonResponse($contacts);
    }

    /**
     * @Route("/sendInvitationRequest/{profile}/{circle}", name="app_SendInvitationRequest")
     * Security("is_granted('ROLE_USER')")
     */
    //TODO: add here. mercure
    public function SendInvitationRequest(Account $profile, Circles $circle, EntityManagerInterface $em,
                                          InvitationRequestRepository $invitationRequestRepository)
    {
        $check = $invitationRequestRepository->checkIfExist($this->getUser()->getId(), $profile->getId());

        if ($check == 0) {
            $invitationRequest = new InvitationRequest();
            $invitationRequest->setWhoInvite($this->getUser());
            $invitationRequest->setWhoInvited($profile);
            $invitationRequest->setCircle($circle);
            $invitationRequest->setCreatedAt(new \DateTime());

            $em->persist($invitationRequest);
            $em->flush();

            return new Response('success');
        } else {
            return new Response('already invited');
        }
    }

    /**
     * @Route("/loadNotifications", name="app_loadNotifications")
     * Security("is_granted('ROLE_USER')")
     */
    //TODO: add here. mercure
    public function LoadNotifications(InvitationRequestRepository $invitationRequestRepository, SerializerInterface $serializer)
    {
        $invitations = $invitationRequestRepository->findLast10($this->getUser()->getId());

        $items = [];
        $i = 0;
        foreach ($invitations as $invitation) {
            $items[$i]['person']['Id'] = $invitation->getWhoInvited()->getId();
            $items[$i]['person']['Username'] = $invitation->getWhoInvited()->getUsername();
            $items[$i]['person']['FirstName'] = $invitation->getWhoInvited()->getFirstName();
            $items[$i]['person']['LastName'] = $invitation->getWhoInvited()->getLastName();
            $items[$i]['CreatedAt'] = $invitation->getCreatedAt();
            $items[$i]['Id'] = $invitation->getId();
            $i++;
        }

        if ($invitations) {
            return new Response(json_encode($items));
        } else {
            return new JsonResponse(['status' => 'empty'], 204);
        }
    }

    /**
     * @Route("/createCircle", name="app_createCircle")
     * Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    //TODO: add here. mercure
    public function createCircle(Request $request, EntityManagerInterface $em, CirclesRepository $circlesRepository)
    {
        if ($request->request->get('_token')) {
            if ($this->isCsrfTokenValid('createCircle', $request->request->get('_token'))) {
                if (!$request->request->get("CircleName") or !$request->request->get("CircleName") == '') {
                    $countCircles = $circlesRepository->checkForName($request->request->get('CircleName'), $this->getUser()->getId());

                    if ($countCircles == 0) {
                        $circle = new Circles();
                        $circle->setName($request->request->get('CircleName'));
                        $circle->setUser($this->getUser());
                        $circle->setImageName($request->request->get('ImageName'));

                        $em->persist($circle);
                        $em->flush();
                    } else {
                        return new JsonResponse(['status' => 'Circle already exist'], 409);
                    }

                    return new JsonResponse(['status' => 'success', 'id'=>$circle->getId()]);
                } else {
                    return new JsonResponse(['status' => 'empty name'], 400);
                }
            } else {
                return new JsonResponse(['status' => "token invalid"], 400);
            }
        } else {
            return new JsonResponse(['status' => "token not provided"], 400);
        }
    }

    /**
     * @Route("/getCircles", name="app_getCircles")
     * Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    //TODO: add here. mercure
    public function getCircles(Request $request, EntityManagerInterface $em, CirclesRepository $circlesRepository)
    {
        if ($request->request->get('_token'))
        {
            if ($this->isCsrfTokenValid('getCircles', $request->request->get('_token'))) {
                $circles = $circlesRepository->getCircles($this->getUser()->getId());

                $items = [];
                $i = 0;
                foreach ($circles as $circle) {
                    $items[$i]['id'] = $circle->getId();
                    $items[$i]['Name'] = $circle->getName();
                    $items[$i]['ImageName'] = $circle->getImageName();
                    $i++;
                }

                if ($circles) {
                    return new Response(json_encode($items));
                } else {
                    return new JsonResponse(['status' => 'empty'], 404);
                }
            } else {
                return new JsonResponse(['status' => "token invalid"], 400);
            }
        } else {
            return new JsonResponse(['status' => "token not provided"], 400);
        }
    }
}
/**
 * TODO: 1. nie dziala wybor kolorow w theme na twig
 * TODO: jesli zalogowany, przeslij gdzies indziej
 */