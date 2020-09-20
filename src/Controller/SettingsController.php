<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
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

    /**
     * @Route("/uploadAvatar", name="api_uploadAvatar")
     * @Security("is_granted('ROLE_USER')")
     */
    public function uploadAvatar(Request $request, PropertyAccessorInterface $propertyAccessor, AccountRepository $acc,
                                EntityManagerInterface $em){
        $file_content = base64_encode(file_get_contents($propertyAccessor->getValue($request->files->get('croppedImage'), 'linkTarget')));

        $imm = new ImageManager(array('driver' => 'gd'));

        $img = $imm->make($file_content);

        $img->mime($propertyAccessor->getValue($request->files->get('croppedImage'), 'mimeType'));

        if ($img->mime == 'image/bmp'){
            $img->extension = '.bmp';
        }elseif ($img->mime == 'image/png'){
            $img->extension = '.png';

        }elseif ($img->mime == 'image/gif'){
            $img->extension = '.gif';
        }elseif ($img->mime == 'image/jpg' or $img->mime == 'image/jpeg'){
            $img->extension = '.jpg';
        }else{
            return new Response('Wrong extension');
        }

        $newFilename = uniqid() . $img->extension;

        //TODO: check for file size

        $img->save("upload/avatars/" . $this->getUser()->getUsername().'/'.$newFilename);

        $user = $acc->find($this->getUser()->getId());

        $user -> setAvatarFileName($newFilename);

        $em->persist($user);
        $em->flush();

        return new JsonResponse('data:'.$img->mime.';base64,'.$file_content);
    }

    /**
     * @Route("/setTheme", name="api_setTheme")
     * @Security("is_granted('ROLE_USER')")
     */
    public function setTheme(Request $request, AccountRepository $acc, EntityManagerInterface $em){
        $user = $acc->find($this -> getUser() -> getId());

        $teheme = $request->request->get('teheme');

        if ($teheme == 'prime'){
            $theme = 1;
        }elseif ($teheme == 'light'){
            $theme = 2;
        }elseif ($teheme == 'dark'){
            $theme = 3;
        }else{
            $theme = 1;
        }

        $user->setThemeDoL($theme);

        $em->persist($user);
        $em->flush();

        return new Response('changed');
    }

}
