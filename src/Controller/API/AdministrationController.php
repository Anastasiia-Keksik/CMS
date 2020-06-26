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
    public function showUsers(LoggerInterface $logger, Request $request, AccountRepository $accountRepository)
    {

        /** @var Account $users */
//        $logger->debug('DataTable request:', (array) $request);
//        $logger->debug('Users data:', $users);
//        $logger->debug('ORDER COLUMN:'. $request->request->get('order')[0]['column']);
//        $logger->debug('ORDER DIR:'. $request->request->get('order')[0]['dir']);
//        $logger->debug('ORDER LIMIT start:'. $request->request->get('start'));
//        $logger->debug('ORDER LIMIT length:'. $request->request->get('length'));

        IF($request->request->get('order')[0]['column']==0)
        {
            $column = 'id';
        }elseif ($request->request->get('order')[0]['column']==1){
            $column="username";
        }elseif ($request->request->get('order')[0]['column']==2){
            $column="firstName";
        }elseif ($request->request->get('order')[0]['column']==3){
            $column="lastName";
        }elseif ($request->request->get('order')[0]['column']==4){
            $column="email";
        }elseif ($request->request->get('order')[0]['column']==5){
            $column="createdAt";
        }elseif ($request->request->get('order')[0]['column']==6){
            $column="lastOnline";
        }elseif ($request->request->get('order')[0]['column']==7) {
            $column = "city";
        }elseif ($request->request->get('order')[0]['column']==8){
            $column="roles";
        }
        //$users = $accountRepository->findAll();

            $users = $accountRepository->findBySearch(
                $request->request->get('search')['value'],
                $column,
                $request->request->get('order')[0]['dir'],
                $request->request->get('start'),
                $request->request->get('length')
            );


        $wynik[] = [
            'ID'=>'',
            'UserName'=>'',
            'FirstName'=>'',
            'LastName'=>'',
            'Email'=>'',
            'CreatedAt'=>'',
            'LastOnline'=>'',
            'From'=>'',
            'Role'=>'',
        ];
        foreach ($users as $user)
        {
            if($user->getLastOnline()==NULL)
            {
                $lastOnline = 'Not yet logged.';
            }else{
                $lastOnline = $user->getLastOnline()->format('Y:m:d H:i:s');
            }
            $wynik[] = [
                'ID'=>$user->getId(),
                'UserName'=>$user->getUsername(),
                'FirstName'=>$user->getFirstName(),
                'LastName'=>$user->getLastName(),
                'Email'=>$user->getEmail(),
                'CreatedAt'=>$user->getCreatedAt()->format('Y:m:d H:i:s'),
                'LastOnline'=>$lastOnline,
                'From'=>$user->getCity().", ". $user->getCountry(),
                'Role'=>$user->getRoles(),
            ];
        }

        return new JsonResponse(['recordsTotal'=>count($users), 'recordsFiltered'=>count($users), 'data'=>$wynik]);
    }
}
