<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Bans;
use App\Entity\Comic;
use App\Entity\ComicEpisode;
use App\Entity\ProfileUserConnection;
use App\Entity\Project;
use App\Entity\ProjectUserConnection;
use App\Entity\SocialPost;
use App\Entity\UserPrintScreens;
use App\Repository\BansRepository;
use App\Repository\ComicCategoriesRepository;
use App\Repository\ComicEpisodeRepository;
use App\Repository\ComicRepository;
use App\Repository\UserPrintScreensRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ComicController extends AbstractController
{
    private $theme;

//    private imagineCacheManager;

    public function __construct()
    {
        if (isset($_COOKIE["theme"])){

            $this->theme = htmlspecialchars($_COOKIE["theme"]);

        }else{

            $this->theme = "#";

        }

//        $this->imagineCacheManager = $liipCache;
    }

    /**
     * @Route("/komiks/{comicid}", name="app_komiks")
     */
    public function index(MainMenuService $mainMenuService, ComicRepository $comicRepo, Comic $comicid,
                          ComicEpisodeRepository $comicEpisodeRepository, Request $request)
    {

        $user = $this->getUser();
        $profile = $user;

        $mainMenu = $mainMenuService->getMenu();

        if ($request->query->get('page')){
            $page = $request->query->get('page');
        }else{
            $page = 1;
        }

        $comicEpisodes = $comicEpisodeRepository->get10Episodes($comicid->getId(), $page);
        $episodesCount = $comicEpisodeRepository->getCountEpisodesPublished($comicid->getId())[0][1];

        $comics = $comicRepo->findMineComics($user->getId());

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/comic/comicList.html.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $user,
            'NotComposed' => true,
            'comics'=>$comics,
            'comic'=>$comicid,
            'comicEpisodes' => $comicEpisodes,
            'episodesCount' => $episodesCount,
            'page' => $page
        ]);
    }

    /**
     * @Route("/OmniViewer/{id}", name="omni_viewer")
     * @param MainMenuService $mainMenuService
     * @param ComicEpisode $episode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function omniViewer(MainMenuService $mainMenuService, ComicEpisode $episode, BansRepository $bansRepository,
                               ComicRepository $comicRepo, ComicEpisodeRepository $comicEpisodeRepository)
    {
        /** @var Account $user */
        $user = $this->getUser();
        $profile = $user;
        $data = new \DateTime('NOW');

        if ($this->isGranted("ROLE_USER"))
        {
            /** @var Bans $checkForBan */
            $checkForBan = $bansRepository->getBan($user->getId());

            if ($checkForBan and $checkForBan->getEndAt()>$data){
                return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/banWindow.twig', [
                    'banned' => $checkForBan->getEndAt()
                ]);
            }
        } else {
            //search for IP ban
        }

        $previousEpisode = $comicEpisodeRepository->getOrderedByNumber($episode->getOrderNumber()-1, $episode->getComic()->getId());
        if ($previousEpisode){
            $previousEpisodeid = $previousEpisode->getId();
        }else{
            $previousEpisodeid = null;
        }
        $nextEpisode = $comicEpisodeRepository->getOrderedByNumber($episode->getOrderNumber()+1, $episode->getComic()->getId());
        if($nextEpisode){
            $nextEpisodeid = $nextEpisode->getId();
        }else{
            $nextEpisodeid = null;
        }
        $comics = $comicRepo->findMineComics($user->getId());

        $mainMenu = $mainMenuService->getMenu();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/comic/omniViewerWorm.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"OmniViewer",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $user,
            'NotComposed' => true,
            'episode' => $episode,
            'comics'=>$comics,
            'previousEpisode' => $previousEpisodeid,
            'nextEpisode' => $nextEpisodeid,
        ]);
    }

    /**
     * @Route("createComic", name="create_comic")
     * @Security("is_granted('ROLE_USER')")
     */
    public function createComic(Request $request, MainMenuService $mainMenuService,
                                ComicCategoriesRepository $comicCatRepo, EntityManagerInterface $em,
                                ComicRepository $comicRepo){

        $user = $this->getUser();
        $profile = $user;

        if ($request->request->get('_token'))
        {
            if ($this->isCsrfTokenValid('createComic', $request->request->get('_token'))){
                $comic = new Comic();

                //MAKE HERE WALIDATION TODO: show to read it

                if ($request->request->get('title')=="")
                {
                    echo "Title can not be null";
                }
                if ($request->request->get('description')=="")
                {
                    echo "Description can not be null";
                }

                if ($request->request->get('title')=="" or $request->request->get('description')==""){
                    Die();
                }

                $comic->setAuthor($this->getUser());
                $comic->setCreatedAt(new \DateTime());
                $comic->setTitle($request->request->get('title'));
                $comic->setDescription(substr($request->request->get('description'),0,501));
                $comic->setBrutality($request->request->get('brutality'));
                $comic->setNudity($request->request->get('nudity'));
                $project = new Project();
                $project->setComic($comic);
                $project->setStatus(0);
                $project->setType(1);
                $projectUserConn = new ProjectUserConnection();
                $projectUserConn->setProject($project);
                $projectUserConn->setUser($this->getUser());
                $projectUserConn->setCreatedAt(new \DateTime());
                $projectUserConn->setRevenue(100);
                // 1 - Comic
                // 2 - Comic episode

                if ($request->request->get('style') == "worm"){
                    $comic->setViewerStyle(1);
                }elseif ($request->request->get('style') == "classic"){
                    $comic->setViewerStyle(2);
                }else{
                    dd('somethign wrong, did you change something?');
                }

                for ($i=1;$i<=3;$i++){
                    $cat[$i]=$comicCatRepo->find($request->request->get('category'.$i));
                }

                if($cat[1]){
                    $comic->setCategory1($cat[1]);
                }else{
                    die('We do not found this category');
                }

                ($cat[2]!=null)? $comic->setCategory2($cat[2]): $comic->setCategory2(null);
                ($cat[2]!=null)? $comic->setCategory3($cat[3]): $comic->setCategory3(null);

                $diary = new SocialPost();

                $file = $request->files->get('thumbnail');

                if ($file->getClientOriginalExtension() !== 'jpg' and $file->getClientOriginalExtension() !== 'gif'
                    and $file->getClientOriginalExtension() !== 'png' and $file->getClientOriginalExtension() !== 'jpeg'){
                    Die('wrong format: .'.$file->getClientOriginalExtension());
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$file->guessExtension();

                $package = new Package(new StaticVersionStrategy('v1'));

                $destination = $this->getParameter('kernel.project_dir')."/public/upload/social/diaryImages/".$this->getUser()->getUsername()."/".$diary->getId();
                $fileAsset = $package->getUrl("/upload/social/diaryImages/".$this->getUser()->getUsername()."/".$diary->getId()."/".$newFilename);

                $file->move($destination, $newFilename);

                $imm = new ImageManager(array('driver'=>'gd'));
                $img = $imm->make($destination.'/'.$newFilename);
                $img -> fit(400);
                $img -> save($destination.'/'.$newFilename);
                //unlink($destination.'/'.$newFilename);


                $diary->setCreatedAt(new \DateTime());
                $diary->setContent('<div style="text-align: center">Comic:</div> <h4 style="text-align: center;"><span style="font-size: 48px; color: black">'.$request->request->get('title').'</span><br><span style="font-size: 14px;">has been created</span></h4><br><div style="text-align: justify;">'.substr($request->request->get('description'),0,501).'</div><br><div style="text-align: center"><img src="'.$fileAsset.'" width="418px"></div><br><br>');
                $diary->setSoftDelete(0);
                $diary->setLikes(0);
                $diary->setAccount($this->getUser());


                $em->persist($diary);
                $em->persist($comic);
                $em->persist($project);
                $em->persist($projectUserConn);
                $em->flush();
            }else{
                dd('invalid token');
            }
        }

        $categories = $comicCatRepo->findAll();

        $comics = $comicRepo->findMineComics($user->getId());

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/comic/addComic.html.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"CreateComic",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $user,
            'NotComposed' => true,
            'comic_title'=>$request->request->get('title'),
            'comic_description'=>$request->request->get('description'),
            'cat1'=>$request->request->get('category1'),
            'cat2'=>$request->request->get('category2'),
            'cat3'=>$request->request->get('category3'),
            'style'=>$request->request->get('style'),
            'brutality'=>$request->request->get('brutality'),
            'nudity'=>$request->request->get('nudity'),
            'categories'=>$categories,
            'comics'=>$comics
        ]);
    }

    /**
     * @Route("addEpisodeForm/{episodeid}", name="add_episode_form")
     * @Security("is_granted('ROLE_USER')")
     */

    public function addEpisodeForm(Request $request, EntityManagerInterface$em, ComicEpisode $episodeid){
        if ($request->request->get(_token)){
            if ($this->isCsrfTokenValid($request->request->get(_token), "add_Episode")){
                if ($episodeid->getComic()->getAuthor() == $this->getUser()){
                    if ($request->request->get('Title') == "" or $request->request->get('Price') == ""){
                        echo ' czegos zapomniales wpisac ';
                        die();
                        //TODO: ladna walidacja
                    }
                    $episodeid->setSound($request->request->get('Title'));
                    $episodeid->setPrice($request->request->get('Price'));
                    $episodeid->setCreatedAt(new \DateTime());

                    $em->persist($episodeid);
                    $em->flush();
                }else{
                    echo 'You  are not the author';
                    Die();
                }
            }else{
                echo ' wrong token';
                Die();
            }
        }else{
            echo ' brak tokenu lub nie wyslana foremka ';
            Die();
        }
    }

    /**
     * @Route("saveTitle/{episodeid}", name="saveTitle")
     * @Security("is_granted('ROLE_USER')")
     */
    public function saveTitle(ComicEpisode $episodeid, EntityManagerInterface $em, Request $request){
        if ($episodeid->getComic()->getAuthor()){
            if ($request->request->get('_token')) {
                if ($this->isCsrfTokenValid('save_title', $request->request->get('_token'))) {

                    $title = $request->request->get('title');

                    $episodeid->setTitle($title);

                    $em->persist($episodeid);
                    $em->flush();

                    return new JsonResponse(['success' => 'ok', 'title'=>$title]);
                }else{
                    echo 'token isnt valid';
                    die();
                }
            }else{
                echo ' where is token? ';
                die();
            }
        }else{
            echo 'you are not the author';
            die();
        }
    }

        /**
     * @Route("addEpisode/{comicid}", name="add_episode")
     * @Security("is_granted('ROLE_USER')")
     */
    public function addEpisode(Comic $comicid, EntityManagerInterface $em){
        if ($comicid->getAuthor() == $this->getUser())
        {

            $Episode = new ComicEpisode();
            $Episode->setTitle('Draft');
            $Episode->setLikes(0);
            $Episode->setViews(0);
            $Episode->setComic($comicid);
            $Episode->setCreatedAt(new \DateTime());
            $Episode->setPublished(0);
            $project = new Project();
            $project->setComicEpisode($Episode);
            $project->setStatus(0);
            $project->setType(2);
            // 1 - Comic
            // 2 - Comic episode
            $projectUserConn = new ProjectUserConnection();
            $projectUserConn->setProject($project);
            $projectUserConn->setUser($this->getUser());
            $projectUserConn->setCreatedAt(new \DateTime());
            $projectUserConn->setRevenue(100);

            $em->persist($project);
            $em->persist($projectUserConn);
            $em->persist($Episode);
            $em->flush();

            return $this->redirectToRoute('edit_episode', ['episodeid'=>$Episode->getId()]);
        }else{
            echo "you are not the author of this comic";
            die;
        }
    }

    /**
     * @Route("saveThumbnail/{episode}", name="save_comic_episode_thumbnail")
     * @Security("is_granted('ROLE_USER')")
     */
    public function uploadEpisodeThumbnail(ComicEpisode $episode, Request $request, EntityManagerInterface $em)
    {
        if ($episode->getComic()->getAuthor() == $this->getUser()) {
            if ($request->request->get('_token')) {
                if ($this->isCsrfTokenValid('send_episode_thumbnail', $request->request->get('_token'))) {
                    $file = $request->files->get('thumbnail');

                    $file -> move($this->getParameter('kernel.project_dir') . "/public/upload/comics/".$episode->getComic()->getId()."/".$episode->getId()."/", "glimpse.png");

                    return new RedirectResponse($this->generateUrl('edit_episode', ['episodeid'=>$episode->getId()]));
                }else{
                    echo 'token is not valid';
                    die;
                }
            }else{
                echo 'where is token?';
                die();
            }
        }else{
            echo ' you are not the author ';
            die();
        }
    }

                    /**
     * @Route("deleteImage/{image}/{episode}", name="delete_image_comic_episode")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteImage($image, ComicEpisode $episode, Request $request, EntityManagerInterface $em){
        $authors = $episode->getProject()->getAccount();
        $isAuthor = false;
        foreach ($authors as $author){
            if ($author->getUser()->getId() == $this->getUser()->getId()){
                $isAuthor = true;
            }
        }
        if ($isAuthor == true){
            if ($request->request->get('_token')){
                if ($this->isCsrfTokenValid('delete_image_episode', $request->request->get('_token'))){
                    $array = $episode->getImages();
                    $key = array_search($image, $array);

                    unset($array[$key]);

                    if (file_exists($this->getParameter('kernel.project_dir')."/public/upload/comics/".$episode->getComic()->getId()."/".$episode->getId()."/".$image)){
                        unlink($this->getParameter('kernel.project_dir')."/public/upload/comics/".$episode->getComic()->getId()."/".$episode->getId()."/".$image);
                        $nameA = explode('.', $image);
                        $last_element = $nameA[count($nameA)-1];
                        array_pop($nameA);
                        $nameA[count($nameA)-1] = $nameA[count($nameA)-1].'_thumb';
                        array_push($nameA, $last_element);
                        $name = implode('.', $nameA);
                        unlink($this->getParameter('kernel.project_dir')."/public/upload/comics/".$episode->getComic()->getId()."/".$episode->getId()."/".$name);
                    }

                    $episode->setImages($array);

                    $em->persist($episode);
                    $em->flush();

                    return new JsonResponse(['image'=>$image]);
                }else{
                    return new JsonResponse(['error'=>'Token not valid']);
                }
            }else{
                return new JsonResponse(['error'=>'Where is token?']);
            }
        }else{
            return new JsonResponse(['error'=>'You are not the author']);
        }
    }

    /**
     * @Route("editEpisode/{episodeid}", name="edit_episode")
     * @Security("is_granted('ROLE_USER')")
     * @param ComicEpisode $episodeid
     * @param MainMenuService $mainMenuService
     * @param ComicRepository $comicsRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editEpisode(ComicEpisode $episodeid, MainMenuService $mainMenuService, ComicRepository $comicsRepo)
    {
        $profile = $this->getUser();
        $comics = $comicsRepo->findMineComics($this->getUser()->getId());

        $thumbnail = file_exists($this->getParameter('kernel.project_dir')."/public/upload/comics/".$episodeid->getComic()->getId()."/".$episodeid->getId()."/glimpse.png");

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/comic/publishEpisode.html.twig', [
            'title'=>'Komiks - '.$_SERVER['APP_NAME'], // tytul komiksu
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"AddEpisode",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'profile' => $profile,
            'user' => $profile,
            'NotComposed' => true,
            'comics'=>$comics,
            'comic'=>$episodeid->getComic(),
            'episode'=>$episodeid,
            'thumbnail'=>$thumbnail
        ]);

    }

    /**
     * @Route("uploadPage/", name="upload_Page")
     * @Security("is_granted('ROLE_USER')")
     */
    public function uploadChart(Request $request, ComicEpisodeRepository $comicEpisodeRepository, EntityManagerInterface $em){


        $file = $request->files->get('page');
        $comicid = $request->request->get('cid');
        $episodeid = $request->request->get('eid');

        $comicEpisode = $comicEpisodeRepository->find($episodeid);

        $valuesArray = $comicEpisode->getImages();

        $length = count($valuesArray);

        if ($length < 101){
            if ($file->getClientOriginalExtension() !== 'jpg' and $file->getClientOriginalExtension() !== 'gif'
                and $file->getClientOriginalExtension() !== 'png' and $file->getClientOriginalExtension() !== 'jpeg'){
                Die('wrong format: .'.$file->getClientOriginalExtension());
            }

            $uniqid = uniqid();

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.$uniqid.'.'.$file->guessExtension();
            $filenamethumb = pathinfo('thumb_'.$file->getClientOriginalName(), PATHINFO_FILENAME).'_'.$uniqid.'.'.$file->guessExtension();

            $destination = $this->getParameter('kernel.project_dir')."/public/upload/comics/$comicid/$episodeid/";

            $file -> move($destination, $filename);

            array_push($valuesArray, $filename);

            natcasesort($valuesArray);

            $comicEpisode->setImages($valuesArray);

            $imm = new ImageManager(array('driver'=>'gd'));

            $img = $imm->make($destination.$filename);

            $img->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img -> save($this->getParameter('kernel.project_dir')."/public/upload/comics/$comicid/$episodeid/$filenamethumb");

            $src = "/upload/comics/$comicid/$episodeid/".$filenamethumb;

            $em->persist($comicEpisode);
            $em->flush();
            return new JsonResponse(['imagefilename'=>$filename,'src'=>$src]);

        }else{

            return new JsonResponse(['error'=>'to many episodes. Already uploaded 100']);
        }

    }

    /**
     * @Route("publishEpisode/{episode}", name="publish_Episode")
     * @Security("is_granted('ROLE_USER')")
     */
    public function publishEpisode(ComicEpisode $episode, ComicEpisodeRepository $comicEpisodeRepository,
                                   Request $request, EntityManagerInterface $em){
        if ($episode->getComic()->getAuthor() == $this->getUser()){
            if ($request->request->get('_token')){
                if ($this->isCsrfTokenValid('publishEpisode', $request->request->get('_token'))){
                    $lastEp = $comicEpisodeRepository->getLastOne($episode->getComic()->getId());
                    $episode->setOrderNumber(($lastEp)?$lastEp->getOrderNumber()+1:1);
                    $episode->setPublished(1);
                    $episode->setPublishedAt(new \DateTime('NOW'));

                    $em->persist($episode);
                    $em->flush();

                    $this->addFlash('success', 'Episode Published');

                    //TODO: ustawic tutaj redirect do epizodu, do viewera
                    return new RedirectResponse($this->generateUrl('app_project', ['id'=>$episode->getProject()->getId()]));
                }
            }
        }
    }

    /**
     * @Route("unpublishEpisode/{episode}", name="unpublish_Episode")
     * @Security("is_granted('ROLE_USER')")
     */
    public function unpublishEpisode(ComicEpisode $episode, Request $request, EntityManagerInterface $em){
        if ($episode->getComic()->getAuthor() == $this->getUser()){
            if ($request->request->get('_token')){
                if ($this->isCsrfTokenValid('publishEpisode', $request->request->get('_token'))){
                    $episode->setPublished(0);
                    $episode->setPublishedAt(NULL);
                    $episode->setOrderNumber(NULL);

                    $em->persist($episode);
                    $em->flush();

                    $this->addFlash('unpublished', "Episode unpublished!");

                    return new RedirectResponse($this->generateUrl('app_project', ['id'=>$episode->getProject()->getId()]));
                }
            }
        }
    }

    /**
     * @Route("/printCheckout", name="stop_print")
     */
    public function banUserByPrint(UserPrintScreensRepository $userPrintScreensRepository, EntityManagerInterface $em)
    {
        if ($this->isGranted("ROLE_USER")){
            /** @var Account $user */
            $user = $this->getUser();
            $date = new \DateTime('now');

            foreach ($user->getComics() as $comic){
                foreach ($comic->getComicEpisodes() as $episode){
                    foreach ($episode->getProject()->getAccount() as $author){
                        if ($author->getUser()->getId() == $user->getId()){
                            return new Response('As Author you wont be banned for printscreens');
                        }
                    }
                }
            }
                /** @var UserPrintScreens $print */
                $print = $userPrintScreensRepository->findUserPrintTry($user->getId());

                if (!$print){
                    $print = new UserPrintScreens();
                    $print->setUser($user);
                    $print->setLastPrintAt($date);
                    $print->setNrOfPrints(1);

                    $em->persist($print);
                    $em->flush();

                    return new Response("you have been added to watch list");
                }else{
                    if ($print->getNrOfPrints() <= 2){
                        if ($date->getTimestamp() - $print->getLastPrintAt()->getTimestamp() < 604800){
                            $print->setLastPrintAt($date);
                            $print->setNrOfPrints($print->getNrOfPrints()+1);

                            $em->persist($print);
                            $em->flush();

                            $endDate = new \DateTime();
                            $endDate->setTimestamp($date->getTimestamp()+900);

                            $ban = new Bans();
                            $ban->setUser($user);
                            $ban->setStartAt($date);
                            $ban->setEndAt($endDate);

                            $em->persist($ban);
                            $em->flush();
                        }else{
                            $print->setLastPrintAt($date);
                            $print->setNrOfPrints($print->getNrOfPrints()+1);

                            $em->persist($print);
                            $em->flush();
                        }
                    } else if ($print->getNrOfPrints() >= 3){
                        if ($date->getTimestamp() - $print->getLastPrintAt()->getTimestamp() < 604800) {
                            $print->setLastPrintAt($date);
                            $print->setNrOfPrints($print->getNrOfPrints()+1);

                            $em->persist($print);
                            $em->flush();

                            $endDate = new \DateTime();
                            $endDate->setTimestamp($date->getTimestamp()+259200);

                            $ban = new Bans();
                            $ban->setUser($user);
                            $ban->setStartAt($date);
                            $ban->setEndAt($endDate);

                            $em->persist($ban);
                            $em->flush();
                        } else {
                            $print->setLastPrintAt($date);
                            $print->setNrOfPrints($print->getNrOfPrints()+1);

                            $em->persist($print);
                            $em->flush();
                        }
                    }
                }
        }else{
            // ip ban
        }
    }
}
