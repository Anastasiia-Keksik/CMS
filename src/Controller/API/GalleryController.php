<?php

namespace App\Controller\API;

use App\Entity\GalleryPhotos;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery_upload", name="app_api_gallery_upload")
     */
    public function uploadImageToGallery(Request $request, LoggerInterface $logger, EntityManagerInterface $em)
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $userUsername = $this->getUser()->getUsername();

        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir')."/public/upload/gallery/".$userUsername;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile -> move($destination, $newFilename);

        $logger -> info('Image ' . $originalFilename . ' uploaded as '. $newFilename);

        $img = new GalleryPhotos();
        if($request->request->get('album'))
        {
            $img ->setAlbum($request->request->get('album'));
        }
        $img->setUnderGalleryId();
        $img->setFileName($newFilename);
        $img->setOriginalFilename($originalFilename);

        $em = $this -> getDoctrine()->getManager();
        $em->persist($img);
        $em->flush();

        $logger -> info('Image - ' . $originalFilename . ' were ADDED TO DATABASE');

        return true;
    }
}
