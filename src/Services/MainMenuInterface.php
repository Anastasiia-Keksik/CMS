<?php


namespace App\Services;

use App\Entity\MainMenuCategory;
use App\Entity\MainMenuChild;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainMenuInterface extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMenuJSON(){
        $menuRepository = $this->em->getRepository(MainMenuCategory::class);
        $menuCategories = $menuRepository -> findBy(["User" => NULL] );


        foreach($menuCategories as $category)
        {
            $childMenuRepository = $this->em->getRepository(MainMenuChild::class);
            $menuChildren = $childMenuRepository -> findBy(["MainMenuCategory" => $category] );

            dump($category);
            foreach ($menuChildren as $child){
                dump($child);
            }
        }
        die;
        for($i=0;$i<=count($menuCategories)-1;$i++)
        {
            dump();
        }            die;

        return true;
    }
}