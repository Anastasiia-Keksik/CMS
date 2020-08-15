<?php

namespace App\Controller\API;

use App\Entity\MainMenuCategory;
use App\Entity\MainMenuChild;
use App\Form\ChoseMenuCategoryFormType;
use App\Form\MakeNewRouteFormType;
use App\Repository\AccountRepository;
use App\Entity\Account;
use App\Repository\MainMenuCategoryRepository;
use App\Repository\MainMenuChildRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdministrationController
 * @package App\Controller\API
 */
class AdministrationController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_users")
     */
    public function showUsers(LoggerInterface $logger, Request $request, AccountRepository $accountRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');//zmienic to na admin TODO
        /** @var Account $users */
//        $logger->debug('DataTable request:', (array) $request);
//        $logger->debug('Users data:', $users);
//        $logger->debug('ORDER COLUMN:'. $request->request->get('order')[0]['column']);
//        $logger->debug('ORDER DIR:'. $request->request->get('order')[0]['dir']);
//        $logger->debug('ORDER LIMIT start:'. $request->request->get('start'));
//        $logger->debug('ORDER LIMIT length:'. $request->request->get('length'));
        $logger->debug('USER LOGGED: '. $this->getUser()->getUsername());

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
        }elseif ($request->request->get('order')[0]['column']==7){
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

            if(in_array('ROLE_ADMIN', $user->getRoles()))
            {
                $role = "ADMIN";
            }else{
                $role = "";
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
                'Role'=>$role,
            ];
        }

        array_splice($wynik,0, 1);

        return new JsonResponse(['recordsTotal'=>count($users), 'recordsFiltered'=>count($users), 'data'=>$wynik]);
    }

    /**
     * @Route("/api/childIndex/", name="app_api_menuChildOrderIndexes")
     */
    public function getManuCatChildrenOrder(Request $request){


    }
}
