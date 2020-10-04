<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Routing\Annotation\Route;

class TestGroundController extends AbstractController
{
    /**
     * @Route("/test/ground", name="test_ground")
     */
    public function index(ChatterInterface $chatter)
    {
        $message = (new ChatMessage('Dostales powiadomienie'))
            ->subject('Powiadomienie')
            ->transport('telegram');

        $chatter->send($message);
        return $this->render('test_ground/index.html.twig', [
            'controller_name' => 'TestGroundController',
        ]);
    }
}
