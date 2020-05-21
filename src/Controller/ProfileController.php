<?php


namespace App\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_main_profile")
     */
    public function viewMineProfile()
    {
        /** @var Account $profile */
        $profile = $this->getUser();
       // $profile -> getUsername();

        return $this->render("profile/profile.html.twig",[
            "profile" => $profile,
        ]);
    }

    /**
     * @Route("/profile/{profile}", name="app_profile")
     */
    public function viewProfile($profile, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Account::class);

        //$userCredentials = $repository->takeUser($profile);
        $userCredentials = $repository->findOneBy(['username' => $profile]);

        if (!$userCredentials)
        {
            throw $this->createNotFoundException('Nie znaleziono takiego uzytkownika');
        }

        return $this->render("profile/profile.html.twig",[
            "profile" => $userCredentials
        ]);
    }
}