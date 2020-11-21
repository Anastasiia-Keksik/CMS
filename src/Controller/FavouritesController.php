<?php

namespace App\Controller;
//amantadyna
use App\Repository\FavouritesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FavouritesController extends AbstractController
{
    /**
     * @Route("/favourites/getAll", name="favourites_getAll")
     * @Security ("is_granted('ROLE_USER')")
     */
    public function getAll(FavouritesRepository $repository)
    {
        $Allrecords = $repository->getAll($this->getUser()->getId());

        foreach ($Allrecords as $rec)
        {
            dump($rec);
        }

        //die();
        return new JsonResponse($Allrecords);
    }

    /**
     * @Route("/favourites/getSM", name="favourites_getSM")
     * @Security ("is_granted('ROLE_USER')")
     */
    public function getSM(FavouritesRepository $repository)
    {
        $SMrecords = $repository->getSM($this->getUser()->getId());

        return new JsonResponse($SMrecords);
    }

    /**
     * @Route("/favourites/getGallery", name="favourites_getGallery")
     * @Security ("is_granted('ROLE_USER')")
     */
    public function getGallery(FavouritesRepository $repository)
    {
        $GalleryPhotos = $repository->getGallery($this->getUser()->getId());

        return new JsonResponse($GalleryPhotos);
    }

    /**
     * @Route("/favourites/getForums", name="favourites_getForums")
     * @Security ("is_granted('ROLE_USER')")
     */
    public function getForums(FavouritesRepository $repository)
    {
        $ForumRecords = $repository->getForums($this->getUser()->getId());

        return new JsonResponse($ForumRecords);
    }

    /**
     * @Route("/favourites/getArt", name="favourites_getArt")
     * @Security ("is_granted('ROLE_USER')")
     */
    public function getArt(FavouritesRepository $repository)
    {
        $creations = $repository->getArt($this->getUser()->getId());

        return new JsonResponse($creations);
    }
}
