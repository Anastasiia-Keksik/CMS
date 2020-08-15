<?php

namespace App\Controller;

use App\Entity\Account;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    /**
     * @Route("/changeUserCredentials/{id}", name="app_changeUserCredentials")
     * @IsGranted("ROLE_USER")
     */
    public function uploadImageAndStuff(Request $request, LoggerInterface $logger, Account $id)
    {
        /**
         * @var UploadedFile $uploadedFile
         */

        if (! $this->getUser()->getId() == $id->getId())
        {
            return false;
        }


        if ($request->files->get('AvatarFile')) {


            $uploadedFile = $request->files->get('AvatarFile');
            $destination = $this->getParameter('kernel.project_dir') . "/public/upload/avatars/" . $id->getUsername();

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName() . '.' . $uploadedFile->guessExtension(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();

            // if($uploadedFile['size']>2000000)
            // {
            //     $this->addFlash('error', "file was to big");
            //     return $this->redirectToRoute("app_main_profile", ['tab'=>'settings']);
            // }

            $uploadedFile->move($destination, $originalFilename);

            if ($id->getAvatarFileName() != null) {
                unlink($destination . '/' . $id->getAvatarFileName());
            }

            $id->setAvatarFileName($originalFilename);

            $logger->info("Image - $originalFilename were as avatar for " . $id->getUsername() . "");
        }
            

        $em = $this -> getDoctrine()->getManager();

        $sygnatura = $id->getSignature();

        $sygnatura->setSignature($request->request->get("Signature"));
       

        if ($request->request->get("country")!="") { $id->setCountry($request->request->get("country")); }
        if ($request->request->get("City")!="") { $id->setCity($request->request->get("City")); } 
        if ($request->request->get("Email")!="") { $id->setEmail($request->request->get("Email")); } 
        if ($request->request->get("Facebook")!="") { $id->setFacebook($request->request->get("Facebook")) ; }
        if ($request->request->get("Twitter")!="") { $id->setTwitter($request->request->get("Twitter")); }
        if ($request->request->get("Linkedin")!="") { $id->setLinkedin($request->request->get("Linkedin")); }
        if ($request->request->get("Reddit")!="") { $id->setReddit($request->request->get("Reddit"));}
        if ($request->request->get("Skype")!="") { $id->setSkype($request->request->get("Skype")); }
        if ($request->request->get("Flickr")!="") { $id->setFlickr($request->request->get("Flickr")); }
        if ($request->request->get("Instagram")!="") { $id->setInstagram($request->request->get("Instagram")); }
        if ($request->request->get("Youtube")!="") { $id->setYoutube($request->request->get("Youtube")); }
        $id->setOccupation('tynkarz');

        // $id->setSignature($sygnatura);




        $em -> persist($sygnatura);
        $em->persist($id);
        $em->flush();

        return $this->redirectToRoute("app_main_profile", ['tab'=>'settings']);
    }
}
