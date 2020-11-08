<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PiramideEventController extends AbstractController
{
    /**
     * @Route("/event/presentation", name="piramide_eventPresentation")
     */
    public function piramidePresentation()
    {
        return $this->render('piramide_event/index.html.twig', [
            'controller_name' => 'PiramideEventController',
        ]);
    }

    /**
     * @Route("/event/join", name="piramide_eventJoin")
     */
    public function piramideJoin()
    {
        return $this->render('piramide_event/index.html.twig', [
            'controller_name' => 'PiramideEventController',
        ]);
    }
}
