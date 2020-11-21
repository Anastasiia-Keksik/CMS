<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\ForumMembers;
use App\Entity\PostsLikes;
use App\Entity\UserForumCategory;
use App\Entity\UserForumForum;
use App\Entity\UserForumPost;
use App\Entity\UserForumRanks;
use App\Entity\UserForumTopic;
use App\Entity\UserPrivateForum;
use App\Repository\AccountRepository;
use App\Repository\ForumMembersRepository;
use App\Repository\PostsLikesRepository;
use App\Repository\UserForumCategoryRepository;
use App\Repository\UserForumForumRepository;
use App\Repository\UserForumPostRepository;
use App\Repository\UserForumRanksRepository;
use App\Repository\UserForumTopicRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\GetContactsService;
use App\Services\MainMenuService;
use App\Services\Validation;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;

class UserForumController extends AbstractController
{
    private $theme;

    private $contacts;

    public function __construct(GetContactsService $contactsRepository)
    {
        if (isset($_COOKIE["theme"])){

            $this->theme = htmlspecialchars($_COOKIE["theme"]);

        }else{

            $this->theme = "#";

        }
    }

    /**
     * @Route("/forum/{id}", name="user_forum")
     * @Security("is_granted('ROLE_USER')")
     * @param UserPrivateForum $forum
     * @param EntityManagerInterface $entityManager
     * @param MainMenuService $mainMenuService
     * @param UserPrivateForumRepository $forumRepository
     * @param UserForumCategoryRepository $catRepo
     * @param UserForumPostRepository $postRepo
     * @param GetContactsService $contactsRepository
     * @return Response
     */
    public function user_forum(UserPrivateForum $forum, EntityManagerInterface $entityManager, MainMenuService $mainMenuService,
                               UserForumCategoryRepository $catRepo,
                               UserForumPostRepository $postRepo, GetContactsService $contactsRepository,
                               ForumMembersRepository $membersRepo)
    {
        $mainMenu = $mainMenuService->getMenu();
        $userRepository = $entityManager->getRepository(Account::class);
        //$forumRepository = $entityManager->getRepository(UserPrivateForumRepository::class);


        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $categories = $catRepo->takeAllInfoFromCategoriesByOrderValue($forum->getId());

        $lastPost = [];
        foreach($categories as $category){
            $forums = $category -> getForumForum();
            foreach ($forums as $insideforum){
                $lastPost[$insideforum ->getId()] = $postRepo->lastPost($insideforum ->getId());
            }
        }

        $member = null;
        $member = $membersRepo->findMember($forum->getId(), $this->getUser()->getId());


        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/ProfileForumList.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'forum'=>$forum,
            'categories'=>$categories,
            'profile'=>$forum->getUserAdmin(),
            'loggedUserUsername' => $this->getUser()->getUsername(),
            'IsItPrivateForum' => true,
            'user'=>$this->getUser(),
            'lastPost'=>$lastPost,
            'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
    }

    //TODO zamienic userId na UserName aby w linku byl username a nie jakas cyferka

    /**
     * @Route("{userId}/forum/MakeCategory/", name="forum_make_new_category_user")
     * @param Account|null $userId
     * @param UserForumCategoryRepository $categoryRepository
     * @param MainMenuService $mainMenuService
     * @param UserInterface $user
     * @param Request $request
     * @param UserPrivateForumRepository $UserForum
     * @param EntityManagerInterface $em
     * @param GetContactsService $contactsRepository
     * @return RedirectResponse|Response
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewCategoryForUser(Account $userId = NULL, UserForumCategoryRepository $categoryRepository,
                                           MainMenuService $mainMenuService, UserInterface $user, Request $request,
                                           UserPrivateForumRepository $UserForum, EntityManagerInterface $em,
                                           GetContactsService $contactsRepository, ForumMembersRepository $membersRepo)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $categoryRepository->takeCategoriesByOrderValue(null);
        $forumid = $request->query->get('forumid');
        $forum = $UserForum->find($forumid);

        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($forum->getId(), $this->getUser()->getId());

        //check if this logged user == slug user id
        if ($userId->getUsername()==$user->getUsername())
        {
            //when user logged == user from slug id then take category hierarchy
            $categories = $categoryRepository->takeCategoriesByOrderValue($userId->getId());
        }else{
            //show 403 inside page when logged user is not the one in the link slug id
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/403.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Error 403",
                'MainMenu' => $mainMenu,
                'theme'=>$this->theme,
                'Contacts' => $contacts,
                'comics' => $comics,
                'user' => $userId,
                'member' => $member
            ]);
        }

        if ($request->request->get('CatName')){
            if ($request->request->get('_token') and $this->isCsrfTokenValid('make_new_category', $request->request->get('_token'))){
                $catEntity = new UserForumCategory();
                $catEntity -> setName($request->request->get('CatName'));
                $catEntity -> setDescription($request->request->get('CatDesc'));
                $catEntity -> setOrderValue($request->request->get('CatNumber'));
                $catEntity -> setIsItUserPrivateForum($forum);

                $em->persist($catEntity);
                $em->flush();

                return new RedirectResponse($this->generateUrl('user_forum', ['id'=>$forum->getId()]));
            }else{
                die('CSRF TOKEN INVALID');
            }
        }

        //TODO zrobic rozne prawa dla roznych userow na prywatnych forumach
        if(!$request->query->get('forumid')){
            //TODO: zrobic jakis sensowny komunikat o bledzie
            die('nie ma takiego forum');
        }else{
//            $forumid = $request->query->get('forumid');
//            $forum = $UserForum->find($forumid);

            //sprawdza czy user ktory edytuje badz dodaje kategorie jest wlascicielem forum TODO: zrobic to sprawdzaniu w oparciu o uprawnienia
            if ($forum->getUserAdmin()->getId() == $user->getId()){
            }else{
                //TODO: zrobic tu komunikat bledu
                die('Niemasz praw administracyjnych');
            }
        }

        // dump($user->getId);die;
        //dd($categories);

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewCategory.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'categories' => $categories,
            'forumid' => $forumid,
            'forum' =>$forum,
            'forumName' => $forum->getName(),
            'forumDesc' => $forum->getDescription(),
            'theme'=>$this->theme,
            'profile'=>$userId,
            'Contacts' => $contacts,
            'comics' => $comics,
            'user' => $userId,
            'member' => $member
        ]);
    }

    /**
     * @Route("{user}/forum/MakeNewForum", name="forum_make_new_forum_user")
     * @Security("is_granted('ROLE_USER')")
     * @param Account|null $user
     * @param UserForumCategoryRepository $categoryRepository
     * @param UserForumForumRepository $forumRepository
     * @param MainMenuService $mainMenuService
     * @param UserInterface $loggedUser
     * @param Request $request
     * @param UserPrivateForumRepository $UserForum
     * @param EntityManagerInterface $em
     * @param GetContactsService $contactsRepository
     * @return RedirectResponse|Response
     */
    public function makeNewForumForUser(Account $user = NULL, UserForumCategoryRepository $categoryRepository,
                                        UserForumForumRepository $forumRepository, MainMenuService $mainMenuService,
                                        UserInterface $loggedUser, Request $request, UserPrivateForumRepository $UserForum,
                                        EntityManagerInterface $em, GetContactsService $contactsRepository,
                                        ForumMembersRepository $membersRepo){
        if ($request->request->get('ForumName')){
            if ($request->request->get('_token') and $this->isCsrfTokenValid('make_new_forum_list', $request->request->get('_token'))){
                $cat = $categoryRepository->find($request->request->get('CatId'));

                $forumEntity = new UserForumForum();
                $forumEntity -> setName($request->request->get('ForumName'));
                $forumEntity -> setDescription($request->request->get('ForumDesc'));
                $forumEntity -> setOrderValue($request->request->get('ForumNumber'));
                $forumEntity -> setCategory($cat);

                $em->persist($forumEntity);
                $em->flush();

                return new RedirectResponse($this->generateUrl('user_forum', ['id'=>$request->query->get('forumid')]));
            }else{
                die('CSRF TOKEN INVALID');
            }
        }
        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();
        //$categories = $categoryRepository->takeCategoriesByOrderValue(null);
        $profile = $user;
        //Check if user id == logged user
        if ($user->getUsername()==$loggedUser->getUsername())
        { //take user forum categories and forum hierarchy
            //$categories = $categoryRepository->takeCategoriesByOrderValue($user->getId());
            $forums = $forumRepository->takeForumsByOrderValue($request->query->get('categoryid'));
            $forum = $UserForum->find($request->query->get('forumid'));
            $category = $categoryRepository->find($request->query->get('categoryid'));
        }else{
            //show 403 inside page when logged user is not the one in the link slug id
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/403.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Error 403",
                'MainMenu' => $mainMenu,
                'theme'=>$this->theme,
                'Contacts' => $contacts,
                'comics' => $comics
            ]);
        }

        $member = null;
        $member = $membersRepo->findMember($forum->getId(), $this->getUser()->getId());

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewForum.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=> $loggedUser,
            'forum'=>$forum,
            'category'=>$category,
            'forums'=>$forums,
            'Profiler'=>$user->getUsername(),
            'theme'=>$this->theme,
            'profile'=>$profile,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
    }

    /**
     * @Route("{kategoria}/forum/EditForumCategory", name="forum_edit_category_user")
     * @Security("is_granted('ROLE_USER')")
     * @param UserForumCategory $kategoria
     * @param UserForumCategoryRepository $CategoriesRepository
     * @param MainMenuService $mainMenuService
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param GetContactsService $contactsRepository
     * @param ForumMembersRepository $membersRepo
     * @return RedirectResponse|Response
     */
    public function editCategory(UserForumCategory $kategoria, UserForumCategoryRepository $CategoriesRepository,
                                 MainMenuService $mainMenuService, Request $request, EntityManagerInterface $em,
                                 GetContactsService $contactsRepository, ForumMembersRepository $membersRepo){
        //zrobic lepsza autoryzacje usera TODO: czytaj z lewej
        if ($this->getUser() == $kategoria->getIsItUserPrivateForum()->getUserAdmin()){
            $CategoryOrder = $CategoriesRepository ->takeCategoriesByOrderValue($kategoria->getIsItUserPrivateForum()->getId());

            if ($request->request->get('_token')){
                if ($this->isCsrfTokenValid('cat_edit', $request->request->get('_token'))){

                    $Categories = $CategoriesRepository->findBy(['IsItUserPrivateForum'=>$kategoria->getIsItUserPrivateForum()]);

                    foreach ($Categories as $category){
                        if($request->request->get('CatNewNumber') > $request->request->get('CatOldNumber')) {
                            if ($category->getOrderValue() > $request->request->get('CatOldNumber')) {
                                $category->setOrderValue($category->getOrderValue() - 1);
                                $em->persist($category);
                            }
                        }
                        elseif ($request->request->get('CatNewNumber') < $request->request->get('CatOldNumber')){
                            if ($category->getOrderValue() < $request->request->get('CatOldNumber')){
                                $category->setOrderValue($category->getOrderValue()+1);
                                $em->persist($category);
                            }
                        }
                    }
                    $kategoria -> setName($request->request->get('CatNewName'));
                    $kategoria -> setDescription($request->request->get('CatNewDesc'));
                    $kategoria -> setOrderValue($request->request->get('CatNewNumber'));
                    $em->persist($kategoria);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('user_forum', ['id'=>$kategoria->getIsItUserPrivateForum()->getId()]));
                }else{
                    die('CSRF TOKEN INVALID');
                }
            }
        }else{
            die('nie masz praw aby edytowac te forum');
        }

        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($kategoria->getIsItUserPrivateForum()->getId(), $this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/EditExistingForumCategory.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'category'=>$kategoria,
            'user'=>$this->getUser(),
            'CategoryOrder'=>$CategoryOrder,
            'profile'=>$kategoria->getIsItUserPrivateForum()->getUserAdmin(),
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member,
            'forum' => $kategoria->getIsItUserPrivateForum()
        ]);
    }

    /**
     * @Route("{forumtable}/forum/EditForumTable/", name="forum_edit_forumtable_user")
     * @Security("is_granted('ROLE_USER')")
     * @param UserForumForum $forumtable
     * @param UserForumCategoryRepository $CategoriesRepository
     * @param MainMenuService $mainMenuService
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserForumForumRepository $forumRepository
     * @param GetContactsService $contactsRepository
     * @param ForumMembersRepository $membersRepo
     * @return RedirectResponse|Response
     */
    public function editForumCategory(UserForumForum $forumtable, UserForumCategoryRepository $CategoriesRepository,
                                      MainMenuService $mainMenuService, Request $request, EntityManagerInterface $em,
                                      UserForumForumRepository $forumRepository, GetContactsService $contactsRepository,
                                      ForumMembersRepository $membersRepo){
        //zrobic lepsza autoryzacje usera TODO: czytaj z lewej
        $forumPrivate = $forumtable->getCategory()->getIsItUserPrivateForum();
        if ($this->getUser() == $forumPrivate->getUserAdmin()){
            $Categories = $CategoriesRepository->takeCategoriesByOrderValue($forumPrivate->getId());

            $forums = $forumRepository ->findBy(['Category'=>$forumtable->getCategory()]);

            if ($request->request->get('_token')){
                if ($this->isCsrfTokenValid('edit_Forum_table', $request->request->get('_token'))){
                    //dump($request->request->get('ForumNewCat')); dump($request->request->get('ForumOldCat'));die;
                    if ($request->request->get('ForumNewCat') != $request->request->get('ForumOldCat'))
                    {
                        $forumsFromNewCat = $forumRepository->takeForumsByOrderValue($request->request->get('ForumNewCat'));
                        if (!$forumsFromNewCat){
                            $forumtable->setOrderValue(1);
                            $em->persist($forumtable);
                        }else {
                            $forumtable->setOrderValue(end($forumsFromNewCat)->getOrderValue());
                            foreach ($forums as $forum) {
                                if ($forum->getOrderValue() > $request->request->get('ForumOldNumber')) {
                                    $forum->setOrderValue($forum->getOrderValue() - 1);
                                    $em->persist($forum);
                                }
                            }
                        }
                    }else{
                        foreach ($forums as $forum){
                            if($request->request->get('ForumNewNumber') > $request->request->get('ForumOldNumber')) {

                                if ($forum->getOrderValue() > $request->request->get('ForumOldNumber')) {
                                    $forum->setOrderValue($forum->getOrderValue() - 1);
                                    $em->persist($forum);
                                }
                            }
                            elseif ($request->request->get('ForumNewNumber') < $request->request->get('ForumOldNumber')){
                                if ($forum->getOrderValue() < $request->request->get('ForumOldNumber')){
                                    $forum->setOrderValue($forum->getOrderValue()+1);
                                    $em->persist($forum);
                                }
                            }
                        }
                        $forumtable -> setOrderValue($request->request->get('ForumNewNumber'));
                    }

                    $forumtable -> setName($request->request->get('ForumNewName'));
                    $forumtable -> setDescription($request->request->get('ForumNewDesc'));
                    $forumtable -> setCategory($CategoriesRepository->find($request->request->get('ForumNewCat')));
                    $em->persist($forumtable);
                    $em->flush();

                    return new RedirectResponse("/".$forumtable->getCategory()->getIsItUserPrivateForum()->getUserAdmin()->getUsername()."/forum");
                }else{
                    die('CSRF TOKEN INVALID');
                }
            }
        }else{
            die('nie masz praw aby edytowac te forum');
        }

        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($forumtable->getCategory()->getIsItUserPrivateForum()->getId(), $this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();
        //dd($Categories);
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/EditExistingForumTable.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'forum'=>$forumtable,
            'forums'=>$forums,
            'user'=>$this->getUser(),
            'CategoryOrder'=>$Categories,
            'profile' => $forumPrivate->getUserAdmin(),
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
    }

    /**
     * @Route("forum/section/{forumtableid}", name="app_forum_threads_list")
     * @Security("is_granted('ROLE_USER')")
     * @param MainMenuService $mainMenuService
     * @param UserForumForum $forumtableid
     * @param PaginatorInterface $paginator
     * @param UserForumTopicRepository $TopicsRepository
     * @param Request $request
     * @param SessionInterface $session
     * @param UserForumPostRepository $postRepo
     * @param GetContactsService $contactsRepository
     * @param ForumMembersRepository $membersRepo
     * @return Response
     */
    public function showForumThreads(MainMenuService $mainMenuService, UserForumForum $forumtableid,
                                     PaginatorInterface $paginator, UserForumTopicRepository $TopicsRepository,
                                     Request $request, SessionInterface $session, UserForumPostRepository $postRepo,
                                     GetContactsService $contactsRepository, ForumMembersRepository $membersRepo){
        $query = $TopicsRepository->findTopicsWithPagination($forumtableid->getId());

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($session->get('tlimit')) ? $session->get('tlimit') : 10 /*limit per page*/
        );

        $iloscPodstron = [];

        foreach($pagination as $watek)
        {
            //dd($postRepo->threadPostsCount($watek->getId()));
            $iloscPodstron[$watek->getId()] = (int) ceil($postRepo->threadPostsCount($watek->getId())[1]/10);
        }

        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($forumtableid->getCategory()->getIsItUserPrivateForum()->getId(), $this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/threads_list.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'profile'=>$forumtableid->getCategory()->getIsItUserPrivateForum()->getUserAdmin(),
            'forumCre'=>$forumtableid->getCategory()->getIsItUserPrivateForum(),
            'forum'=>$forumtableid,
            'thread_pagination'=>$pagination,
            'iloscPodstron'=>$iloscPodstron,
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
    }

    /**
     * @Route("thread/{threadid}/{threadname}", name="app_forum_thread_user", defaults={"threadname"=""})
     * @Security("is_granted('ROLE_USER')")
     */
    public function openThread(MainMenuService $mainMenuService, PaginatorInterface $paginator,
                               UserForumPostRepository $forumPostRepo, Request $request,
                               UserForumTopic $threadid, SessionInterface $session, EntityManagerInterface $em,
                               LoggerInterface $logger, PostsLikesRepository $like, GetContactsService $contactsRepository,
                               ForumMembersRepository $membersRepo){
        $referer_array = explode('/', parse_url($request->headers->get('referer'))['path']);
        if ($referer_array[1] != 'thread' and $referer_array[2] != $threadid->getId()) {
            $views = $threadid->getViews();
            $threadid->setViews($views + 1);

            $em->persist($threadid);
            $em->flush();
        }

        //$pagination = $forumPostRepo->findPostsMinePagination($threadid->getId(), ($request->query->get('page'))? $request->query->get('page') : 0, ($session->get('plimit')) ? $session->get('plimit') : 10);
//
        $logger->info('REQUEST rejkjawik: '.$request->attributes->get('_request_type'));
//
        $query = $forumPostRepo->findPostsForThreadWithPagination($threadid->getId());

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            ($session->get('plimit') != null) ? $session->get('plimit') : 10 /*limit per page*/
        );
        $postsLikes = [];
        foreach ($pagination as $post){
            $wynik = $like->getPostLike($post->getId(), $this->getUser()->getId());

            $postsLikes[$post->getId()] = ($wynik)? 1 : 0 ;
        }
        $mainMenu = $mainMenuService->getMenu();

        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($threadid->getForum()->getCategory()->getIsItUserPrivateForum()->getId(), $this->getUser()->getId());

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/Thread_View.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'profile'=>$threadid->getForum()->getCategory()->getIsItUserPrivateForum()->getUserAdmin(),
            'forumCre'=>$threadid->getForum()->getCategory()->getIsItUserPrivateForum(),
            'forum'=>$threadid->getForum(),
            'thread'=>$threadid,
            'posts_pagination'=>$pagination,
            'postsLikes'=>$postsLikes,
            'page'=> $request->query->get('page'),
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
        //return new Response('done');
    }

    /**
     * @Route("makeNewThread/{forumtableid}", name="app_forum_make_new_thread")
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewThread(MainMenuService $mainMenuService, UserForumForum $forumtableid,
                                  PaginatorInterface $paginator, UserForumTopicRepository $TopicsRepository, Request $request,
                                  EntityManagerInterface $em, GetContactsService $contactsRepository,
                                  ForumMembersRepository $membersRepo){
        //$query = $TopicsRepository->findTopicsWithPagination($forumtableid);

        //TODO: Zrobic w ogole sprawdzanie, czy mamy dostep do forum w ktorym robimy nowa kategorie czy forum

        //sprawdzanie czy nalezysz do forum
        if (true){
            if(true){
                //tutaj sie zrobi na haslo (w sesji)
            }


            if ($request->request->get('_token')) {
                if ($this->isCsrfTokenValid('new_thread', $request->request->get('_token'))) {
                    //sprawdzaj czy Tytul postu zostal nadany oraz czy posiada jakakolwiek tresc, w razie czego, cofnij do poprzedniej strony i pokaz, blad
                    if(!$request->request->get('ThreadTitle') or !$request->request->get('NewThreadPostContent')){
                        die('walidacja Cie nieprzepuscila,cos zostawiles puste');
                    }
                    $topicEntity = new UserForumTopic();
                    $topicEntity->setAuthor($this->getUser());
                    $topicEntity->setCreatedAt(new \DateTime());
                    $topicEntity->setViews(0);
                    $topicEntity->setReplies(0);
                    $topicEntity->setLastPostAt(new \DateTime());
                    $topicEntity->setForum($forumtableid);
                    $topicEntity->setSticky(0);
                    $topicEntity->setTitle($request->request->get('ThreadTitle'));
                    $topicEntity->setSoftDelete(0);

                    $postEntity = new UserForumPost();
                    $postEntity->setAuthor($this->getUser());
                    $postEntity->setSoftDelete(0);
                    $postEntity->setCreatedAt(new \DateTime());
                    $postEntity->setLikes(0);
                    $postEntity->setContent($request->request->get('NewThreadPostContent'));
                    $postEntity->setForumTopic($topicEntity);

                    $em->persist($postEntity);
                    $em->persist($topicEntity);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('app_forum_threads_list', ["forumtableid"=>$forumtableid->getId()]));
                }else{
                    die('CSRF TOKEN IS INVALID');
                }
            }


            //sprawdzanie przy validacjiczy wyslalesna to wlasnieforum co trzeba
        }else{
            die('nie masz praw do odwiedzania tego forum');
        }


        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $member = null;
        $member = $membersRepo->findMember($forumtableid->getCategory()->getIsItUserPrivateForum()->getId(), $this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewThread.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'forum'=>$forumtableid,
            'forumCre'=>$forumtableid->getCategory()->getIsItUserPrivateForum(),
            'profile'=>$forumtableid->getCategory()->getIsItUserPrivateForum()->getUserAdmin(),
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member
        ]);
    }

    /**
     * @Route("forum/post/like/{postid}", name="app_forum_post_like")
     * @Security("is_granted('ROLE_USER')")
     */
    public function likePost(UserForumPost $postid, EntityManagerInterface $em, PostsLikesRepository $like,
                             AccountRepository $account)
    {
        $wynik = $like->getPostLike($postid->getId(), $this->getUser());
        $user = $account->find($this->getUser());

        if(!$wynik){
            $postLike = new PostsLikes();
            $likes = $postid->getLikes();
            $likes ++;
            $postid->setLikes($likes);
            $postLike->setPost($postid);
            $postLike->setUser($user);
            $json = 'added';
            $em->persist($postLike);
        }else{
            $likes = $postid->getLikes();
            $likes --;
            $postLike = $like->getPostLike($postid->getId(), $this->getUser()->getId());
            $postid->setLikes($likes);
            $em->remove($postLike);
            $json = 'removed';
        }


        $em->persist($postid);
        $em->flush();

        return new JsonResponse(['response'=>$json, 'likes'=>$postid->getLikes()]);
    }

    /**
     * @Route("/forum/NewPost/{thread}", name="user-forum-new-post")
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewPost(UserForumTopic $thread, EntityManagerInterface $entityManager, Request $request,
                                UserForumPostRepository $forumPostRepo, PaginatorInterface $paginator,
                                SessionInterface $session)
    {
        if ($request->request->get('_token')) {
            if ($this->isCsrfTokenValid('new-post', $request->request->get('_token'))) {
                //TODO: walidacja
                if(!$request->request->get('ContentPost')){
                    die('walidacja Cie nieprzepuscila,cos zostawiles puste');
                }else{

                    $postEntity = new UserForumPost();
                    $postEntity->setAuthor($this->getUser());
                    $postEntity->setSoftDelete(0);
                    $postEntity->setCreatedAt(new \DateTime());
                    $postEntity->setLikes(0);
                    $postEntity->setContent($request->request->get('ContentPost'));
                    $postEntity->setForumTopic($thread);

                    $entityManager->persist($postEntity);
                    $entityManager->flush();

                    $query = $forumPostRepo->findPostsForThreadWithPagination($thread->getId());

                    $pagination = $paginator->paginate(
                        $query, /* query NOT result */
                        $request->query->getInt('page', 1), /*page number*/
                        ($session->get('plimit')) ? $session->get('plimit') : 10 /*limit per page*/
                    );

                    $page = ceil($pagination->getTotalItemCount() / $request->query->getInt('perpage', 10));

                    return new RedirectResponse('/thread/'.$thread->getId().'?page='.$page.'#'.$postEntity->getId());
                }
            }else{
                die('bad token');
            }
        }else{
            die('no token');
        }
    }

    /**
     * @Route("/createForum/{user}", name="create_forum")
     * @Security("is_granted('ROLE_USER')")
     */
    public function createForum($user, AccountRepository $accountRepository, MainMenuService $mainMenuService,
                                UserPrivateForumRepository $forumRepository, Request $request, EntityManagerInterface $em,
                                Validation $valid, GetContactsService $contactsRepository)
    {
        $user = $accountRepository->findOneBy(["username"=>$user]);

        $forums = $forumRepository->findBy(["UserAdmin"=>$user, 'softDelete'=>0]);

        $active = false;

        if ($forums) {
            $active = true;
        }


        $forumDesc = "";
        $forumName = "";

        // this goto fucntion is somewhat easter egg of mine. I started programing thanks to this function and i wanted to used somewhere. Leave it be.
        if ($request->request->get('_token')) {
            if ($this->isCsrfTokenValid('createForum', $request->request->get('_token'))) {

                $nameValid = $valid->string255($request->request->get('forumName'));
                if($nameValid != true){
                    $this->addFlash('error', 'Forum name'.$nameValid);
                    goto end;
                }

                $descValid = $valid->string255($request->request->get('forumDesc'));
                if($descValid != true)
                {
                    $this->addFlash('error', 'Description'.$descValid);
                    goto end;
                }

                $passValid = $valid->password($request->request->get('password'));
                if ($passValid != true){
                    $this->addFlash('error', $passValid);
                    goto end;
                }

                if(strlen(trim($request->request->get('forumName'))) < 1){
                    $this->addFlash('error', 'Forum name need to have at lest one character');

                    goto end;
                }

                if(strlen(trim($request->request->get('password'))) < 3 and strlen(trim($request->request->get('password'))) > 0){
                    $this->addFlash('error', 'Password need to have at least 3 characters');

                    goto end;
                }

                $forum = new UserPrivateForum();

                $forum->setName(trim($request->request->get('forumName')));
                $forum->setDescription(trim($request->request->get('forumDesc')));
                $forum->setSoftDelete(0);
                $forum->setCreatedAt(new \DateTime());
                $forum->setPassword($request->request->get('password'));
                $forum->setUserAdmin($user);

                $member = new ForumMembers();

                $member->setMember($user);
                $member->setForum($forum);
                $member->setCreatedAt(new \DateTime());
                $member->setRank(1);
                $member->setRole(['ADMIN']);
                $member->setPending(0);

                $em -> persist($member);
                $em -> persist($forum);
                $em -> flush();

                return new RedirectResponse($this->generateUrl('user_forum', ['id'=>$forum->getId()]));

                end:
                $forumDesc = trim($request->request->get('forumDesc'));
                $forumName = trim($request->request->get('forumName'));
            }
        }
        $contacts = $contactsRepository->getContacts($this->getUser()->getId());
        $comics = $contactsRepository->getComics($this->getUser()->getId());

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/create.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Create forum",
            'MainMenu' => $mainMenu,
            'theme' => $this->theme,
            'user' => $user,
            'profile' => $user,
            'active'=>$active,
            'forumDesc'=>$forumDesc,
            'forumName'=>$forumName,
            'Contacts' => $contacts,
            'comics' => $comics
        ]);
    }

    /**
     * @Route("/forum/join/{forumid}", name="app_forum_join")
     * @Security("is_granted('ROLE_USER')")
     * @param UserPrivateForum $forumid
     * @param ForumMembersRepository $forumMembersRepository
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function forumJoin(UserPrivateForum $forumid, ForumMembersRepository $forumMembersRepository,
                              EntityManagerInterface $em){
        $member = $forumMembersRepository->findMember($forumid->getId(), $this->getUser()->getId());

        if ($member !== null){
            Die('User already is part of this forum');
        }

        $member = new ForumMembers();

        $member->setMember($this->getUser());
        $member->setForum($forumid);
        $member->setCreatedAt(new \DateTime());
        $member->setRole(['USER']);
        $member->setPending(0);
        $member->setRank(1);

        $em->persist($member);
        $em->flush();

        return new RedirectResponse($this->generateUrl('user_forum', ['id'=>$forumid->getId()]));
    }

    /**
     * @Route("/forum/settings/{id}", name="app_forum_settings")
     * @Security("is_granted('ROLE_USER')")
     * @param UserPrivateForum $forum
     * @param Request $request
     * @param MainMenuService $mainMenuService
     * @param GetContactsService $getContacts
     * @param ForumMembersRepository $membersRepo
     * @param UserForumForumRepository $sectionRepo
     * @return Response
     */
    public function forumSettings(UserPrivateForum $forum, Request $request, MainMenuService $mainMenuService,
                                  GetContactsService $getContacts, ForumMembersRepository $membersRepo,
                                  UserForumForumRepository $sectionRepo){
        $request->query->get('forumid');

        $member = null;
        $member = $membersRepo->findMember($forum->getId(), $this->getUser()->getId());

        $roles = [];
        //dd($forum->getRoles());
        $y = $forum->getRoles();
        if (!empty($y)){
            foreach ($forum->getRoles() as $key => $val) {
                $newVal = [];
                dump($val);
                if (!isset($val['administrator'])) {
                    foreach ($val as $key2 => $val2) {
                        if (is_array($val2)) {
                            $sectionName = $sectionRepo->find($key2);
                            $newVal2 = "<b>" . $sectionName->getName() . ':</b><br> - + ';

                            $i = 0;
                            foreach ($val2 as $key3 => $val3) {
                                if ($val3 == "on") {
                                    if ($i != 0) {
                                        $newVal2 .= ', ';
                                    }
                                    $newVal2 .= '<span style="color:green">' . $key3 . '</span>';

                                } else {
                                    if ($i != 0) {
                                        $newVal2 .= ', ';
                                    }
                                    $newVal2 .= '<span style="color:rosybrown">' . $key3 . '</span>';

                                }
                                $i++;
                            }
                            $newVal2 .= '<br>';
                        } else {

                            $sectionName = $sectionRepo->find($key2);

                            $newVal2 = "<b>" . $sectionName->getName() . ':</b> ' . '"' . $val2 . '" <br>';
                        }
                        $newVal[$key2] = $newVal2;
                        //die();
                    }
                }else{
                    $newVal = '<span style="color: darkred"><b>Administrator</b></span>';
                }
                $roles[$key] = $newVal;

            }
        }

        $contacts = $getContacts->getContacts($this->getUser()->getId());
        $comics = $getContacts->getComics($this->getUser()->getId());
        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/ForumSettings.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum Settings",
            'MainMenu' => $mainMenu,
            'forum'=>$forum,
            'profile'=>$forum->getUserAdmin(),
            'loggedUserUsername' => $this->getUser()->getUsername(),
            'IsItPrivateForum' => true,
            'user'=>$this->getUser(),
            'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member,
            'roles' => $roles,
            'editroles' => $forum->getRoles(),

        ]);
    }

    /**
     * @Route ("/forum/members/{id}", name="app_forum_members")
     * @param Request $request
     * @param UserPrivateForum $forum
     * @param MainMenuService $mainMenuService
     * @param GetContactsService $getContacts
     * @param ForumMembersRepository $membersRepo
     * @param PaginatorInterface $paginator
     * @param AccountRepository $account
     * @return Response
     */
    public function forumMembers(Request $request, UserPrivateForum $forum, MainMenuService $mainMenuService,
                                 GetContactsService $getContacts, ForumMembersRepository $membersRepo,
                                 PaginatorInterface $paginator, AccountRepository $account)
    {

        $member = null;
        $member = $membersRepo->findMember($forum->getId(), $this->getUser()->getId());

        if ($request->query->get('letter')) {
            $query = $membersRepo->findMembersFromLetter($forum->getId(), $request->query->get('letter'));
        }elseif ($request->query->get('string')){
            $query = $membersRepo->findMembersBySearch($forum->getId(), $request->query->get('string'));
        }else{
            $query = $membersRepo->findAllMembers($forum->getId());
        }

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            24 /*limit per page*/
        );

        $contacts = $getContacts->getContacts($this->getUser()->getId());
        $comics = $getContacts->getComics($this->getUser()->getId());
        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/ForumMembers.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'mod' => 'forum',
            'pageName'=>"Forum Members",
            'MainMenu' => $mainMenu,
            'forum'=>$forum,
            'profile'=>$forum->getUserAdmin(),
            'IsItPrivateForum' => true,
            'user'=>$this->getUser(),
            'SocialPosts'=>$_SERVER['SOCIAL_POSTS'],
            'theme'=>$this->theme,
            'Contacts' => $contacts,
            'comics' => $comics,
            'member' => $member,
            'members' => $pagination
        ]);
    }

    /**
     * @Route("/forum/saveNew/settings/{forum}", name="app_forum_settings_saveNew")
     */
    public function forumSettingsSaveNew(UserPrivateForum $forum, Request $request, EntityManagerInterface $em)
    {
        //TODO: sprawdzaj czy ma prawda admina
        if ($forum->getUserAdmin() == $this->getUser())
        {
            if ($this->isCsrfTokenValid('forumSettings', $request->request->get('_token'))){
                $name = strip_tags($request->request->get('name'));
                $roles = $forum->getRoles();
                if ($request->request->get('administration')=='on'){
                    $roles += ([
                        $name =>[
                            'administrator'=>['true']
                        ]
                    ]);
                }else{
                    $roles += ([
                        $name => []
                    ]);
                    for ($i=1;$i<$request->request->get('iterations'); $i++){
                        $itId = $request->request->get('Dzialid_'.$i);
                        if($request->request->get("full_".$itId)=='on'){
                            $roles[$name][$itId] = [
                                'edycja' => "on",
                                'kasacja' => "on",
                                'przenoszenie' => "on",
                            ];
                        }else{
                            $roles[$name][$itId] = [
                                'edycja' => $request->request->get('edition_'.$itId),
                                'kasacja' => $request->request->get('deleting_'.$itId),
                                'przenoszenie' => $request->request->get('moving_'.$itId),
//                                'kick' => $request->request->get('kicking_'.$itId),
//                                'ban' => $request->request->get('baning_'.$itId),
//                                'changeranks' => $request->request->get('changeranks_'.$itId)
                            ];
                        }
                    }
                }

                $forum->setRoles($roles);
                $em->persist($forum);
                $em->flush();
            }else{
                Die('token not valid');
            }
        }else{
            Die("It is not your forum");
        }

        return new RedirectResponse($this->generateUrl("app_forum_settings", ['id' =>  $forum->getId()]));
    }

    /**
     * @Route("/forum/remove/role/{forum}", name="app_forum_remove_role")
     */
    public function removeRole(UserPrivateForum $forum, Request $request, EntityManagerInterface $em) {
        if ($forum->getUserAdmin() == $this->getUser()) {
            $roles = $forum->getRoles();
            unset($roles[$request->query->get('role')]);
            $forum->setRoles($roles);

            $em->persist($forum);
            $em->flush();
        }else{
            Die('You are not the owner of that forum,neither the administrator');
        }

        return new RedirectResponse($this->generateUrl("app_forum_settings", ['id' => $forum->getId()]));
    }

    /**
     * @Route("/forum/saveOld/settings/{forum}", name="app_forum_settings_saveOld")
     */
    public function forumSettingsSaveOld(UserPrivateForum $forum, Request $request, EntityManagerInterface $em,
                                         ForumMembersRepository $membersRepository)
    {
        /** @var ForumMembers $member */
        $member = $membersRepository->findMember($forum->getId(), $this->getUser()->getId());
        $memberRole = $member->getRole();
        if ($forum->getUserAdmin() == $this->getUser() or isset($memberRole['administrator']))
        {
            if ($this->isCsrfTokenValid('forumSettings', $request->request->get('_token'))){
                $name = strip_tags($request->request->get('name'));
                $roles = $forum->getRoles();
                unset($roles[$name]);
                if ($request->request->get('administration')=='on'){
                    $roles += ([
                        $name =>[
                            'administrator'=>['true']
                        ]
                    ]);
                }else{
                    $roles += ([
                        $name => []
                    ]);
                    for ($i=1;$i<$request->request->get('iterations'); $i++){
                        $itId = $request->request->get('Dzialid_'.$i);
                        if($request->request->get("full_".$itId)=='on'){
                            $roles[$name][$itId] = [
                                'edycja' => "on",
                                'kasacja' => "on",
                                'przenoszenie' => "on",
                            ];
                        }else{
                            $roles[$name][$itId] = [
                                'edycja' => $request->request->get('edition_'.$itId),
                                'kasacja' => $request->request->get('deleting_'.$itId),
                                'przenoszenie' => $request->request->get('moving_'.$itId)
                            ];
                        }
                    }
                }

                $forum->setRoles($roles);
                $em->persist($forum);
                $em->flush();
            }else{
                Die('token not valid');
            }
        }else{
            Die("It is not your forum");
        }

        return new RedirectResponse($this->generateUrl("app_forum_settings", ['id' =>  $forum->getId()]));
    }

    /**
     * @Route("/forum/save/newRank/{forum}", name="app_forum_save_new_rank")
     */
    public function saveNewRank(UserPrivateForum $forum, Request $request, ForumMembersRepository $membersRepository,
                                EntityManagerInterface $em, UserForumRanksRepository $ranksRepository){
        /** @var ForumMembers $member */
        $member = $membersRepository->findMember($forum->getId(), $this->getUser()->getId());
        $memberRole = $member->getRole();
        if ($forum->getUserAdmin() == $this->getUser() or isset($memberRole['administrator']))
        {
            if ($this->isCsrfTokenValid('forumSettings', $request->request->get('_token'))) {
                $error = 0;

                $lastRank = $ranksRepository->findLastOne($forum->getId());

                //dd($lastRank);

                $rank = new UserForumRanks();

                $rank->setForum($forum);

                $start = $request->request->get('start');
                $finish = $request->request->get('finish');

                if (! $start > $lastRank[1]){
                    $this->addFlash('error', "Start has to be greater than previous ranks finish number.");
                    $error = 1;
                }

                if ($start >=0 and $finish > $start){
                    $rank->setStart($start);
                    $rank->setFinish($finish);
                }elseif ($start == "" and $finish == ""){
                    $rank->setSpecial(1);
                }else{
                    $this->addFlash('error', "Start has to be bigger than zero, finish has to be bigger than start.");
                    $error = 1;
                }

                /**
                 * @var UploadedFile $uploadedFile
                 */
                $uploadedFile = $request->files->get('image');
                $destination = $this->getParameter('kernel.project_dir') . "/public/upload/forum/ranks/" . $forum->getId();

                if ($uploadedFile->getClientOriginalExtension() !== 'jpg' and $uploadedFile->getClientOriginalExtension() !== 'gif'
                    and $uploadedFile->getClientOriginalExtension() !== 'png' and $uploadedFile->getClientOriginalExtension() !== 'jpeg'){
                    Die('wrong format: .'.$uploadedFile->getClientOriginalExtension());
                }

                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();

                if($uploadedFile->getSize() > 400000)
                {
                    $this->addFlash('error', "file size was to big");
                    $error = 1;
                }

                if (getimagesize($uploadedFile)[0] > 200 or getimagesize($uploadedFile)[1] > 90){
                    $this->addFlash('error', "file dimension was to big");
                    $error = 1;
                }

                if ($error == 1)
                {
                    return $this->redirectToRoute("app_forum_settings", ['id'=>$forum->getId()]);
                }

                $uploadedFile->move($destination, $newFilename);

                $rank->setImage($newFilename);
                $rank->setName($request->request->get('title'));

                $em->persist($rank);
                $em->flush();
            }else{
                Die('Invalid Token');
            }
        }else{
            Die('Not enough rights');
        }

        return new RedirectResponse($this->generateUrl("app_forum_settings", ['id' =>  $forum->getId()]));
    }
}
