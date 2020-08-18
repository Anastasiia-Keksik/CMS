<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\ForumCategory;
use App\Entity\UserForumCategory;
use App\Entity\UserForumForum;
use App\Entity\UserForumPost;
use App\Entity\UserForumTopic;
use App\Entity\UserPrivateForum;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumForumRepository;
use App\Repository\UserForumCategoryRepository;
use App\Repository\UserForumForumRepository;
use App\Repository\UserForumTopicRepository;
use App\Repository\UserPrivateForumRepository;
use App\Services\MainMenuService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserForumController extends AbstractController
{

    /**
     * @Route("/{profile}/forum", name="user_forum")
     */
    public function user_forum(string $profile, EntityManagerInterface $entityManager, MainMenuService $mainMenuService,
                               UserPrivateForumRepository $forumRepository, UserForumCategoryRepository $catRepo)
    {
        $mainMenu = $mainMenuService->getMenu();
        $userRepository = $entityManager->getRepository(Account::class);
        //$forumRepository = $entityManager->getRepository(UserPrivateForumRepository::class);

        $user = $this->getUser();
        if($user)
        {
            $username = $user->getUsername();
        }else{
            $username = NULL;
        }

        $userCredentials = $userRepository->findOneBy(['username' => $profile]);

        if (!$userCredentials)
        {
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/404.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
                'error_msg' => 'There is no username with this name.',
                'middle_error_msg' => '',
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
            ]);
        }

        $forum = $forumRepository->findOneBy(['UserAdmin'=>$userCredentials->getId()]);
        $categories = $catRepo->takeAllInfoFromCategoriesByOrderValue($forum->getId());
        if (!$forum)
        {
            return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/security/404.inside.error.page.twig', [
                'title'=>'Forum - '.$_SERVER['APP_NAME'],
                'lang'=>'pl',
                'APP_NAME'=>$_SERVER['APP_NAME'],
                'logoSite'=>$_SERVER['SHOW_LOGO'],
                'navFooter'=>$_SERVER['NAV_FOOTER'],
                'footer'=>$_SERVER['FOOTER'],
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
                'error_msg' => 'This username does not have forum',
                'middle_error_msg' => '',
                'lower_error_msg' => '<a href="javascript:history.back();">Go Back!</a>',
            ]);
        }

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/ProfileForumList.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'forum'=>$forum,
            'categories'=>$categories,
            'thisForumUserAdmin'=>$userCredentials,
            'loggedUserUsername' => $username,
            'IsItPrivateForum' => true,
        ]);
    }

    //TODO zamienic userId na UserName aby w linku byl username a nie jakas cyferka
    /**
     * @Route("{userId}/forum/MakeCategory/", name="forum_make_new_category_user")
     * @param null $userId
     * @param ForumCategoryRepository $categoryRepository
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewCategoryForUser(Account $userId = NULL, UserForumCategoryRepository $categoryRepository,
                                           MainMenuService $mainMenuService, UserInterface $user, Request $request,
                                           UserPrivateForumRepository $UserForum, EntityManagerInterface $em)
    {
        $mainMenu = $mainMenuService->getMenu();
        $categories = $categoryRepository->takeCategoriesByOrderValue(null);

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
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
            ]);
        }

        if ($request->request->get('CatName')){
            if ($request->request->get('_token') and $this->isCsrfTokenValid('make_new_category', $request->request->get('_token'))){
                $catEntity = new UserForumCategory();
                $catEntity -> setName($request->request->get('CatName'));
                $catEntity -> setDescription($request->request->get('CatDesc'));
                $catEntity -> setOrderValue($request->request->get('CatNumber'));
                $catEntity -> setIsItUserPrivateForum($userId->getUserPrivateForum());

                $em->persist($catEntity);
                $em->flush();

                return new RedirectResponse("/".$userId->getUsername()."/forum");
            }else{
                die('CSRF TOKEN INVALID');
            }
        }

        //TODO zrobic rozne prawa dla roznych userow na prywatnych forumach
        if(!$request->query->get('forumid')){
            //TODO: zrobic jakis sensowny komunikat o bledzie
            die('nie ma takiego forum');
        }else{
            $forumid = $request->query->get('forumid');
            $forum = $UserForum->find($forumid);

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
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'categories' => $categories,
            'forumid' => $forumid,
            'forumName' => $forum->getName(),
            'forumDesc' => $forum->getDescription()
        ]);
    }

    /**
     * @Route("{user}/forum/MakeNewForum", name="forum_make_new_forum_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewForumForUser(Account $user = NULL, UserForumCategoryRepository $categoryRepository,
                                        UserForumForumRepository $forumRepository, MainMenuService $mainMenuService,
                                        UserInterface $loggedUser, Request $request, UserPrivateForumRepository $UserForum,
                                        EntityManagerInterface $em){
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

                return new RedirectResponse("/".$user->getUsername()."/forum");
            }else{
                die('CSRF TOKEN INVALID');
            }
        }

        $mainMenu = $mainMenuService->getMenu();
        //$categories = $categoryRepository->takeCategoriesByOrderValue(null);

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
                'pageName'=>"Forum",
                'MainMenu' => $mainMenu,
            ]);
        }

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewForum.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=> $loggedUser,
            'forum'=>$forum,
            'category'=>$category,
            'forums'=>$forums,
            'Profiler'=>$user->getUsername(),
        ]);
    }

    /**
     * @Route("{kategoria}/forum/EditForumCategory", name="forum_edit_category_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function editCategory(UserForumCategory $kategoria, UserForumCategoryRepository $CategoriesRepository,
                                 MainMenuService $mainMenuService, Request $request, EntityManagerInterface $em){
        //zrobic lepsza autoryzacje usera TODO: czytaj z lewej
        if ($this->getUser() == $kategoria->getIsItUserPrivateForum()->getUserAdmin()){
            $CategoryOrder = $CategoriesRepository ->takeCategoriesByOrderValue($kategoria->getIsItUserPrivateForum());

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

                    return new RedirectResponse("/".$kategoria->getIsItUserPrivateForum()->getUserAdmin()->getUsername()."/forum");
                }else{
                    die('CSRF TOKEN INVALID');
                }
            }
        }else{
            die('nie masz praw aby edytowac te forum');
        }

        $mainMenu = $mainMenuService->getMenu();

        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/EditExistingForumCategory.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'category'=>$kategoria,
            'user'=>$this->getUser(),
            'CategoryOrder'=>$CategoryOrder
        ]);
    }

    /**
     * @Route("{forumtable}/forum/EditForumTable/", name="forum_edit_forumtable_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function editForumCategory(UserForumForum $forumtable, UserForumCategoryRepository $CategoriesRepository,
                                 MainMenuService $mainMenuService, Request $request, EntityManagerInterface $em,
                                 UserForumForumRepository $forumRepository){
        //zrobic lepsza autoryzacje usera TODO: czytaj z lewej
        if ($this->getUser() == $forumtable->getCategory()->getIsItUserPrivateForum()->getUserAdmin()){
            $Categories = $CategoriesRepository->takeCategoriesByOrderValue($forumtable->getCategory()->getIsItUserPrivateForum());

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

        $mainMenu = $mainMenuService->getMenu();
        //dd($Categories);
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/EditExistingForumTable.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'forum'=>$forumtable,
            'forums'=>$forums,
            'user'=>$this->getUser(),
            'CategoryOrder'=>$Categories
        ]);
    }

    /**
     * @Route("forum/{forumid}/{forumtableid}", name="app_forum_threads_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showForumThreads(MainMenuService $mainMenuService, UserPrivateForum $forumid, UserForumForum $forumtableid,
                                    PaginatorInterface $paginator, UserForumTopicRepository $TopicsRepository, Request $request){
        $query = $TopicsRepository->findTopicsWithPagination($forumtableid);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            25 /*limit per page*/
        );

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/threads_list.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'profile'=>$forumid->getUserAdmin(),
            'forumCre'=>$forumid,
            'forum'=>$forumtableid,
            'thread_pagination'=>$pagination
        ]);
    }

    /**
     * @Route("thread/{threadid}/{threadname}", name="app_forum_thread_user", defaults={"threadname"=""})
     * @Security("is_granted('ROLE_USER')")
     */
    public function openThread(MainMenuService $mainMenuService, PaginatorInterface $paginator,
                               UserForumTopicRepository $TopicsRepository, Request $request){
        $query = $TopicsRepository->findTopicsWithPagination($forumtableid);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            1 /*limit per page*/
        );

        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/threads_list.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'profile'=>$forumid->getUserAdmin(),
            'forumCre'=>$forumid,
            'forum'=>$forumtableid,
            'thread_pagination'=>$pagination
        ]);
    }

    /**
     * @Route("makeNewThread/{forumtableid}", name="app_forum_make_new_thread")
     * @Security("is_granted('ROLE_USER')")
     */
    public function makeNewThread(MainMenuService $mainMenuService, UserForumForum $forumtableid,
                                  PaginatorInterface $paginator, UserForumTopicRepository $TopicsRepository, Request $request,
                                  EntityManagerInterface $em){
        $query = $TopicsRepository->findTopicsWithPagination($forumtableid);

        //TODO: Zrobic w ogole sprawdzanie, czy mamy dostep do forum w ktorym robimy nowa kategorie czy forum

        //sprawdzanie czy nalezysz do forum
        if (true){
            if(true){
                //tutaj sie zrobi na haslo (w sesji)
            }


            if ($request->request->get('_token')) {
                if ($this->isCsrfTokenValid('new_thread', $request->request->get('_token'))) {
                    if()
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
                }else{
                    die('CSRF TOKEN IS INVALID');
                }
            }


            //sprawdzanie przy validacjiczy wyslalesna to wlasnieforum co trzeba
        }else{
            die('nie masz praw do odwiedzania tego forum');
        }



        $mainMenu = $mainMenuService->getMenu();
        return $this->render($_SERVER['DEFAULT_TEMPLATE'].'/forum/makeNewThread.twig', [
            'title'=>'Forum - '.$_SERVER['APP_NAME'],
            'lang'=>'pl',
            'APP_NAME'=>$_SERVER['APP_NAME'],
            'logoSite'=>$_SERVER['SHOW_LOGO'],
            'navFooter'=>$_SERVER['NAV_FOOTER'],
            'footer'=>$_SERVER['FOOTER'],
            'pageName'=>"Forum",
            'MainMenu' => $mainMenu,
            'user'=>$this->getUser(),
            'forum'=>$forumtableid,
            'forumCre'=>$forumtableid->getCategory()->getIsItUserPrivateForum(),
            'profile'=>$forumtableid->getCategory()->getIsItUserPrivateForum()->getUserAdmin(),
        ]);
    }
}
