<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Passwords;
use App\Form\LoginFormType;
use App\Form\RecoverPasswordFormType;
use App\Form\RegisterFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class
SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, MainMenuService $mainMenuService): Response
    {
        $mainMenu = $mainMenuService->getMenu();
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        $form = $this->createForm(LoginFormType::class);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title'=>'title',
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Profile",
            'MainMenu' => $mainMenu,
            'loginForm'=>$form->createView()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/recovery", name="app_recovery")
     */
    public function recoverPassword(){

        $form = $this->createForm(RecoverPasswordFormType::class);

        $this->addFlash('success',"Hasło zostało pomyślnie wysłane na twój email.");
        $this->addFlash('success',"Nie znaleziono takiego emaila");

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/security/recoverPassword.html.twig",[
            'lang'=>'pl',
            'title'=>'Recover Password in'. $_SERVER['APP_NAME'],
            'recoverPasswordForm'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator$formAuthenticator)
    {
        $form = $this->createForm(RegisterFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            /** @var Account $account */
            $account = $form->getData();
            $account->setTemplate($_SERVER['DEFAULT_TEMPLATE']);//TODO: pobierac default template z bazy

            $account->setPassword($passwordEncoder->encodePassword(
                $account,
                $form['plainPassword']->getData()
            ));

            $account->setCreatedAt(new \DateTime());

            if (true === $form['terms']->getData())
            {
                $account->terms();
            }

            if ($form['newsletter']==null)
            {
                $account->setNewsletter('0');
            }

            $password = new Passwords();
            $password->setPassword($form['plainPassword']->getData());
            $password->setEmail($form['email']->getData());
            $password->setUser($account);

            $em = $this -> getDoctrine()->getManager();
            $em->persist($account);
            $em->persist($password);
            $em->flush();

            $this->addFlash('success',"Zarejestrowano pomyślnie.");

            return $guardHandler->authenticateUserAndHandleSuccess(
                $account,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/security/register.html.twig",[
            'lang'=>'pl',
            'title'=>'Registern in'. $_SERVER['APP_NAME'],
            'registerForm'=>$form->createView(),
        ]);
    }
}
