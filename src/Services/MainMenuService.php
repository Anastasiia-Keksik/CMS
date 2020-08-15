<?php


namespace App\Services;

use App\Entity\MainMenuCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\CheckPathRoutes;

class MainMenuService extends AbstractController
{
    private $em;
    private $checkPathRoute;

    public function __construct(EntityManagerInterface $em, CheckPathRoutes $checkPath)
    {
        $this->em = $em;
        $this->checkPathRoute = $checkPath;
    }

    public function getMenu(){

        $menuRepository = $this->em->getRepository(MainMenuCategory::class);

        $menu = $menuRepository -> findOrderBy();
        $Error = "Developer menu warnings: <br>";
        $Errorit = 0;
        /** @var MainMenuCategory $menu */
        /** @var MainMenuCategory $menuRekord */
        $i=0;
        foreach ($menu as $menuRekord)
        {
            if($menuRekord->getHidden()==1)
            {
                array_splice($menu, $i, 1);
            }
            $i++;

            $y=0;
            foreach ($menuRekord->getMainMenuChildren() as $child) {
                if($child->getHidden()==1)
                {
                    array_splice($child, $y, 1);
                }
                $y++;
                $bag = $this->checkPathRoute->checkPath($child->getUrlValue());
                if ($bag != "pass")
                {
                    $Errorit++;
                    $child->setUrlValue('app_home');
                    $Error = $Error . "$Errorit. " . $bag . " - temporarly changed for home link" . "<br>";
                }
            }
        }
        if(!$this->get('session')->getFlashBag()->has('menuvalueerror') && $Errorit > 0){
            $this->addFlash('menuvalueerror', $Error);
        }

        return $menu;
    }

    public function getMenuForAdmin(){

        $menuRepository = $this->em->getRepository(MainMenuCategory::class);

        $menu = $menuRepository -> findOrderBy();
        $Error = "Developer menu warnings: <br>";
        $Errorit = 0;
        /** @var MainMenuCategory $menu */
        /** @var MainMenuCategory $menuRekord */
        foreach ($menu as $menuRekord)
        {

            foreach ($menuRekord->getMainMenuChildren() as $child) {
                $bag = $this->checkPathRoute->checkPath($child->getUrlValue());
                if ($bag != "pass")
                {
                    $Errorit++;
                    $child->setUrlValue('app_home');
                    $Error = $Error . "$Errorit. " . $bag . " - temporarly changed for home link" . "<br>";
                }
            }
        }
        if(!$this->get('session')->getFlashBag()->has('menuvalueerror') && $Errorit > 0){
            $this->addFlash('menuvalueerror', $Error);
        }

        return $menu;
    }

}