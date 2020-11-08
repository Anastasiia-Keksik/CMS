<?php

namespace App\Controller;

use App\Repository\UserPrivateForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function test(UserPrivateForumRepository $forumRepo, EntityManagerInterface $em){
        $id = '130c60c7-1f13-4db9-9f73-b2bbef11ee0c';

        $forum = $forumRepo->find($id);

        $forum->setRoles([
            'administrator'=>[
                'administrator'=>['true']
            ],
            'administrator (custom)'=>[
                'dzial1'=>['all'],
                'dzial2'=>['all'],
                'dzial3'=>['all'],
                'ogolne'=>['akceptacja urzytkownikow', 'usuwanie urzytkownikow']
            ],
            'moderator'=>[
                'dzial1'=>['kasacja', 'edycja'],
                'dzial2'=>[],
                'dzial3'=>['edycja', 'przenoszenie'],
                'ogolne'=>[]
            ]
            ]);

        $em->persist($forum);
        $em->flush();

        Die("works");

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
