<?php

namespace App\Controller\API;

use App\Repository\AccountRepository;
use App\Entity\Account;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_users")
     */
    public function showUsers(LoggerInterface $logger, AccountRepository $accountRepository)
    {
        $request = Request::createFromGlobals();
        //GET POST
        $zmienna=$request;

        $users = $accountRepository->findAll();
        /** @var Account $users */
        $logger->debug('DataTable request:', (array) $zmienna);
        $logger->debug('Users data:', $users);

        foreach ($users as $user)
        {
            $users = array(
                'ID'=>$user->getId(),
                'UserName'=>$user->getUsername(),
                'FirstName'=>$user->getFirstName(),
                'LastName'=>$user->getLastName(),
                'Email'=>$user->getEmail(),
                'CreatedAt'=>$user->getCreatedAt()->format('U:m:h'),
                'LastOnline'=>$user->getFirstName()->format("u:h:m"),
                'From'=>$user->getCity().", ". $user->getCountry(),
                'Role'=>$user->getRoles(),
            );
        }

        return new JsonResponse(['recordsTotal'=>count($users), 'data'=>$users]);
    }
}
