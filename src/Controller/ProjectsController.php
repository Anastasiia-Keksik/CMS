<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Comic;
use App\Entity\Project;
use App\Entity\ProjectUserConnection;
use App\Repository\AccountRepository;
use App\Repository\ComicEpisodeRepository;
use App\Repository\ComicRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserConnectionRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    private $theme;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])) {

            $this->theme = $_COOKIE["theme"];

        } else {

            $this->theme = "#";

        }
    }

    //TODO: PODODAWAC SPRAWDZANIE UPRAWNIEN PRZY ZMIANACH!

    /**
     * @Route("/projects", name="app_projects")
     * @Security("is_granted('ROLE_USER')")
     * @param MainMenuService $mainMenuService
     * @param ComicRepository $comicRepo
     * @param GetContactsService $getContactsService
     * @return Response
     */
    public function projects(MainMenuService $mainMenuService, ComicRepository $comicRepo, GetContactsService $getContactsService, ProjectRepository $projectsRepo)
    {
        /** @var Account $user */
        $user = $this->getUser();

        $mainMenu = $mainMenuService->getMenu();

        $projects = $projectsRepo->findComicProjects($user);
        //dd($projects[0]);

        $presentUsers = [];
        $pUi = 0;
        foreach ($projects as $project){
            foreach ($project->getAccount() as $author){
                if (empty($presentUsers)){
                    $presentUsers[$pUi]['Id'] = $author->getUser()->getId();
                    $presentUsers[$pUi]['Username'] = $author->getUser()->getUsername();
                    $presentUsers[$pUi]['FirstName'] = $author->getUser()->getFirstName();
                    $presentUsers[$pUi]['LastName'] = $author->getUser()->getLastName();
                    $presentUsers[$pUi]['VisibleName'] = $author->getUser()->getVisibleName();
                    $presentUsers[$pUi]['AvatarFileName'] = $author->getUser()->getAvatarFileName();
                    $presentUsers[$pUi]['Occupation'] = $author->getUser()->getOccupation();
                    $presentUsers[$pUi]['LastOnline'] = $author->getUser()->getLastOnline();
                    $pUi++;
                }else{
                    $found = 0;
                    foreach ($presentUsers as $presentUser){
                        if ($presentUser['Id'] == $author->getUser()->getId()){
                            $found = 1;
                            break;
                        }
                    }
                    if ($found != 1)
                    {
                        $presentUsers[$pUi]['Id'] = $author->getUser()->getId();
                        $presentUsers[$pUi]['Username'] = $author->getUser()->getUsername();
                        $presentUsers[$pUi]['FirstName'] = $author->getUser()->getFirstName();
                        $presentUsers[$pUi]['LastName'] = $author->getUser()->getLastName();
                        $presentUsers[$pUi]['VisibleName'] = $author->getUser()->getVisibleName();
                        $presentUsers[$pUi]['AvatarFileName'] = $author->getUser()->getAvatarFileName();
                        $presentUsers[$pUi]['Occupation'] = $author->getUser()->getOccupation();
                        $presentUsers[$pUi]['LastOnline'] = $author->getUser()->getLastOnline();
                        $pUi++;
                    }
                }
            }
        }

        $comics =  $comicRepo->findMineComics($this->getUser()->getId());
        $contacts = $getContactsService->getContacts($user->getId());

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/Projects/projectsList.twig', [
            'title'=>'Projekty - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Projects",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $user,
            'user' => $user,
            //'NotComposed' => true,
            'comics'=>$comics,
            "Contacts" => $contacts,
            "projects" => $projects,
            'presentUsers' => $presentUsers
        ]);
    }

    /**
     * @Route("/project/{id}", name="app_project")
     * @Security("is_granted('ROLE_USER')")
     * @param $project
     * @param MainMenuService $mainMenuService
     * @param ComicRepository $comicRepo
     * @param GetContactsService $getContactsService
     * @return Response
     */
    public function project(Project $project, MainMenuService $mainMenuService, ComicRepository $comicRepo, GetContactsService $getContactsService)
    {
        /** @var Account $user */
        $user = $this->getUser();

        $mainMenu = $mainMenuService->getMenu();

        $isAuthor=false;
        foreach ($project->getAccount() as $author){
            if ($author->getUser()->getId() == $user->getId())
            {
                $isAuthor = true;
            }
        }
        $presentUsers = [];
        $pUi = 0;
        if ($isAuthor == true){
            if ($project->getType() == 1) {
                $episodes = $project->getComic()->getComicEpisodes();

                foreach ($episodes as $comicEpisode) {
                    foreach ($comicEpisode->getProject()->getAccount() as $author) {
                        if ($author->getUser()->getId() == $this->getUser()->getId()) {
                            $comicEpisode->setIsMine(true);
                            $comicEpisode->setRevenue($author->getRevenue());
                            $comicEpisode->setIncome($author->getIncome());
                        }

                        if (empty($presentUsers)){
                            $presentUsers[$pUi]['Id'] = $author->getUser()->getId();
                            $presentUsers[$pUi]['Username'] = $author->getUser()->getUsername();
                            $presentUsers[$pUi]['FirstName'] = $author->getUser()->getFirstName();
                            $presentUsers[$pUi]['LastName'] = $author->getUser()->getLastName();
                            $presentUsers[$pUi]['VisibleName'] = $author->getUser()->getVisibleName();
                            $presentUsers[$pUi]['AvatarFileName'] = $author->getUser()->getAvatarFileName();
                            $presentUsers[$pUi]['Occupation'] = $author->getUser()->getOccupation();
                            $presentUsers[$pUi]['LastOnline'] = $author->getUser()->getLastOnline();
                            $pUi++;
                        }else{
                            $found = 0;
                            foreach ($presentUsers as $presentUser){
                                if ($presentUser['Id'] == $author->getUser()->getId()){
                                    $found = 1;
                                    break;
                                }
                            }
                            if ($found != 1)
                            {
                                $presentUsers[$pUi]['Id'] = $author->getUser()->getId();
                                $presentUsers[$pUi]['Username'] = $author->getUser()->getUsername();
                                $presentUsers[$pUi]['FirstName'] = $author->getUser()->getFirstName();
                                $presentUsers[$pUi]['LastName'] = $author->getUser()->getLastName();
                                $presentUsers[$pUi]['VisibleName'] = $author->getUser()->getVisibleName();
                                $presentUsers[$pUi]['AvatarFileName'] = $author->getUser()->getAvatarFileName();
                                $presentUsers[$pUi]['Occupation'] = $author->getUser()->getOccupation();
                                $presentUsers[$pUi]['LastOnline'] = $author->getUser()->getLastOnline();
                                $pUi++;
                            }
                        }

                    }
                }
            }
        }else{
            die('You are not the author of this project.');
        }

        $comics =  $comicRepo->findMineComics($this->getUser()->getId());
        $contacts = $getContactsService->getContacts($user->getId());

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/Projects/projectPage.twig', [
            'title'=>'Projekty - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Projects",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $user,
            'user' => $user,
            'NotComposed' => true,
            'comics'=>$comics,
            "Contacts" => $contacts,
            'project' => $project,
            'projectEpisodes' => ($project->getType() == 1)?$episodes:null,
            'presentUsers' => $presentUsers
        ]);
    }

    /**
     * @Route ("/ComicDescriptionSave/{comic}", name="comic_changeDescription")
     * @Security("is_granted('ROLE_USER')")
     */
    public function changeDescription(Comic $comic, Request $request, EntityManagerInterface $em){
        if ($request->request->get('_token'))
        {
            if ($this->isCsrfTokenValid('description_token', $request->request->get('_token'))) {
                $comic->setDescription($request->request->get('description'));

                $em->persist($comic);
                $em->flush();

                return $this->redirectToRoute('app_project', ['id'=> $request->request->get('project')]);
            }else{
                return new Response("token invalid");
            }
        }else{
            return new Response("error");
        }
    }

    /**
     * @Route ("/SavePersonToProject", name="comic_SavePersonToProject")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @param AccountRepository $accountRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function SavePersonToProject(Request $request, ProjectRepository $projectRepository,
                                        AccountRepository $accountRepository, EntityManagerInterface $em,
                                        ProjectUserConnectionRepository $projectUserConnectionRepository){
        if ($request->request->get('_token'))
        {
            if ($this->isCsrfTokenValid('save_person', $request->request->get('_token'))) {
                $check = $projectUserConnectionRepository->countMine($request->request->get('project'), $request->request->get('person'));

                if ($check == 0){
                    $project = $projectRepository->find($request->request->get('project'));
                    $user = $accountRepository->find($request->request->get('person'));

                    //FUCK PSR
                    $PaUConn = new ProjectUserConnection();

                    $PaUConn -> setUser($user);
                    $PaUConn -> setProject($project);
                    $PaUConn -> setCreatedAt(new \DateTime());
                    //revenue nowe musi byc na zero w razie gdyby jesli juz bylo ustawione aby nie resetowalo poprzedniego rozlozenia.
                    $PaUConn -> setRevenue(0);
                    $PaUConn -> setIncome(0);

                    $em->persist($PaUConn);
                    $em->flush();

                    return new Response("success");
                }

                return new Response('User already in');
            }else{
                return new Response("token invalid");
            }
        }else{
            return new Response("error");
        }
    }
}
