<?php


namespace App\Services;

use App\Entity\MainMenuCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainMenuService extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMenu(){
        $menuRepository = $this->em->getRepository(MainMenuCategory::class);

        return $menuRepository -> findBy(['User' => NULL]);
    }

}