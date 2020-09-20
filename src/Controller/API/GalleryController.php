<?php

namespace App\Controller\API;

use App\Entity\GalleryPhotos;
use App\Entity\Gallery;
use App\Repository\AccountRepository;
use App\Repository\GalleryAlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery_upload", name="app_api_gallery_upload")
     */
    public function uploadImageToGallery(Request $request, LoggerInterface $logger, EntityManagerInterface $em,
                                         AccountRepository $userRepo, GalleryAlbumRepository $albumRepo)
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $userUsername = $this->getUser()->getUsername();

        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir')."/public/upload/gallery/".$userUsername;

        if ($uploadedFile->getClientOriginalExtension() !== 'jpg' and $uploadedFile->getClientOriginalExtension() !== 'gif'
            and $uploadedFile->getClientOriginalExtension() !== 'png' and $uploadedFile->getClientOriginalExtension() !== 'jpeg'){
            Die('wrong format: .'.$uploadedFile->getClientOriginalExtension());
        }

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile -> move($destination, $newFilename);

        $logger -> info('Image ' . $originalFilename . ' uploaded as '. $newFilename);

        $user = $userRepo->findOneBy(['username'=>$userUsername]);

        $img = new GalleryPhotos();
        if($request->request->get('album'))
        {
            $album = $albumRepo->find($request->request->get('album'));
            $img ->setAlbum($album);
        }

        $img->setUnderGalleryId($user->getGallery());
        $img->setFileName($newFilename);
        $img->setOriginalFilename($originalFilename.'.'.$uploadedFile->getClientOriginalExtension());
        $img->setUploadedAt(new \DateTime());
        $img->setSoftDelete(0);

        $em->persist($img);
        $em->flush();

        //dodaj liczbe fot do galerii

        $logger -> info('Image - ' . $originalFilename . ' were ADDED TO DATABASE');

        return new JsonResponse(['status'=>'success']);
    }
}
