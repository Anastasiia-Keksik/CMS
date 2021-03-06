<?php

namespace App\Controller\API;

use App\Entity\SocialPost;
use App\Entity\SocialPostComment;
use App\Form\ChoseMenuCategoryFormType;
use App\Form\MakeNewRouteFormType;
use App\Repository\AccountRepository;
use App\Repository\SocialPostRepository;
use App\Repository\SocialPostCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class SocialController
 * @package App\Controller\API
 */
class SocialController extends AbstractController
{
    private $maxCommentLength = 600;

    /**
     * @Route("/api/get_social_posts", name="api_app_getSocialPosts")
     * @IsGranted("ROLE_USER")
     */
    public function showUsers(LoggerInterface $logger, Request $request, SocialPostRepository $socialPostRepository,
                              SocialPostCommentRepository $underCommentsRepo, AccountRepository $accountRepository)
    {
        //TODO: check if user who sees it (pobiera to) have dostep do tego (not black listed, friend etc)
        $id = $request->request->get('profile');
        $profile = $accountRepository->find($id);
        //$id = $request->query->get('profile');

        $socialPosts = $socialPostRepository->loadNewPostsOffset($id, $request->request->get('offset'));

        $posts = [];
        $postsIt=0;

        foreach ($socialPosts as $post){
            if($post->getSoftDelete()==false){
                /** @var SocialPost $post */
                $comments_length = $underCommentsRepo->countAllComments($post->getId());
                $main_comments_length = $underCommentsRepo->countMainComments($post->getId());
                $posts[$postsIt] = [
                    'Id'=>$post->getId(),
                    'Content'=>$post->getContent(),
                    'Author'=>$post->getAccount()->getId(),
                    'AuthorUsername'=>$post->getAccount()->getUsername(),
                    'AuthorFirstName'=>$post->getAccount()->getFirstName(),
                    'AuthorLastName'=>$post->getAccount()->getLastName(),
                    'AuthorAvatarFileName'=>$post->getAccount()->getAvatarFileName(),
                    'AuthorOccupation'=>$post->getAccount()->getOccupation(),
                    'createdAt'=>$post->getCreatedAt(),
                    'modifiedAt'=>$post->getModifiedAt(),
                    'Likes'=>$post->getLikes(),
                    'Comments'=>[],
                    'Comments_length'=>$comments_length[0]['1'],
                    'MainCommentsLength'=>$main_comments_length[0]['1'],
                    'BGFilename' => $post->getBackgroundFilename(),
                    'VisibleName' => $post->getAccount()->getVisibleName(),
                    'BGcolor' => $post->getBGcolor(),
                    'BGopacity' => $post->getBGopacity(),
                ];
                $commentIt=0;
                $comments = $underCommentsRepo->findNewestComments( $post->getId(),'3');
                foreach($comments as $comment)
                {
                    if($comment->getSoftDelete()==false and $comment->getUnderAnotherComment() == null){
                        array_push($posts[$postsIt]['Comments'], [
                            'Id'=>$comment -> getId(),
                            'Content'=>$comment-> getContent(),
                            'Author'=>$comment-> getAuthor()->getId(),
                            'AuthorUsername'=>$comment->getAuthor()->getUsername(),
                            'AuthorFirstName'=>$comment->getAuthor()->getFirstName(),
                            'AuthorLastName'=>$comment->getAuthor()->getLastName(),
                            'AuthorAvatarFileName'=>$comment->getAuthor()->getAvatarFileName(),
                            'createdAt'=>$comment->getCreatedAt(),
                            'modifiedAt'=>$comment->getModifiedAt(),
                            'CommentConversation'=>[],
                            'VisibleName' => $comment->getAuthor()->getVisibleName()
                        ]);

                        $underComments = $underCommentsRepo->findBy(['underAnotherComment'=>$comment->getId()]);
                        foreach ($underComments as $underComment) {
                            array_push($posts[$postsIt]['Comments'][$commentIt]['CommentConversation'], [
                                'Id'=>$underComment -> getId(),
                                'Content'=>$underComment-> getContent(),
                                'Author'=>$underComment-> getAuthor()->getId(),
                                'AuthorUsername'=>$underComment->getAuthor()->getUsername(),
                                'AuthorFirstName'=>$underComment->getAuthor()->getFirstName(),
                                'AuthorLastName'=>$underComment->getAuthor()->getLastName(),
                                'AuthorAvatarFileName'=>$underComment->getAuthor()->getAvatarFileName(),
                                'createdAt'=>$underComment->getCreatedAt(),
                                'modifiedAt'=>$underComment->getModifiedAt(),
                                'VisibleName' => $underComment->getAuthor()->getVisibleName()
                            ]);
                        }
                    }
                    $commentIt++;
                }
                $postsIt++;
            }
        }
        //dd($posts);
        if ($posts==null)
        {
            return new Response('empty');
        }
        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_loadPost.html.twig",[
            'posts'=>$posts,
            'profile'=>$profile,
            'user'=>$this->getUser()
        ]);
    }

    /**
     * @Route("/api/addCommentToPost/{id}", name="api_app_addCommentToPost")
     * @IsGranted("ROLE_USER")
     */
    public function addComment(Request $request, \App\Entity\SocialPost $id, EntityManagerInterface $em,
                               SocialPostCommentRepository $postCommentRepository){
        $token = $request->request->get('_token');
        $data = new \DateTime('NOW');

        if ($data->getTimestamp() - $postCommentRepository->lastComment($this->getUser()->getId())->getCreatedAt()->getTimestamp() > 10){


            if ($request->request->get('Content') == ""){
                return new Response('Empty content');
            } elseif (strlen($request->request->get('Content')) > $this->maxCommentLength){
                $content = substr($request->request->get('Content'), 0, $this->maxCommentLength);
            } else {
                $content = $request->request->get('Content');
            }

            if ($this->isCsrfTokenValid('comment', $token)){
                $newComment = new SocialPostComment();
                $newComment -> setAuthor($this->getUser());
                $newComment -> setContent($content);
                $newComment -> setCreatedAt($data);
                $newComment -> setSoftDelete('0');
                $newComment -> setUnderAnotherComment(null);
                $newComment -> setLikes(0);

                $id->addSocialPostComment($newComment);

                $em->persist($newComment);
                $em->persist($id);
                $em->flush();
            }else{
                //TODO: bad csrf
            }
            return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_addCommentToPost.html.twig", [
                'status'=>'ok',
                'Author'=>$this->getUser(),
                'Content'=>$request->request->get('Content'),
                'CreatedAt'=> $data,
                'ConversationId'=>null,
                'commentId'=>$newComment->getId(),
            ]);
        }else{
            return new Response('<span class="answer-wait-10">Wait at least 10 seconds until next answer</span>');
        }
    }

    /**
     * @Route("/api/addCommentToComment/{id}", name="api_app_addCommentToComment")
     * @IsGranted("ROLE_USER")
     */
    public function addCommentToConversation(Request $request, \App\Entity\SocialPostComment $id, EntityManagerInterface $em,
                                             SocialPostCommentRepository $postCommentRepository){
        $token = $request->request->get('_token');

        $data = new \DateTime('now');

        if ($data->getTimestamp() - $postCommentRepository->lastComment($this->getUser()->getId())->getCreatedAt()->getTimestamp() > 10) {

            if ($request->request->get('Content') == "") {
                return new Response('Empty content');
            } elseif (strlen($request->request->get('Content')) > $this->maxCommentLength) {
                $content = substr($request->request->get('Content'), 0, $this->maxCommentLength);
            } else {
                $content = $request->request->get('Content');
            }

            if ($this->isCsrfTokenValid('comment', $token)) {
                $newComment = new SocialPostComment();
                $newComment->setAuthor($this->getUser());
                $newComment->setContent($request->request->get('Content'));
                $newComment->setCreatedAt($data);
                $newComment->setSoftDelete('0');
                $newComment->setLikes(0);

                $id->addSocialPostComment($newComment);

                $em->persist($newComment);
                $em->persist($id);
                $em->flush();
            } else {
                //TODO: bad csrf
            }

            if ($request->request->get('right') === "1") {
                return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/profile/parts/api_addConversationComment.html.twig", [
                    'status' => 'ok',
                    'user' => $this->getUser(),
                    'content' => $request->request->get('Content'),
                    'CreatedAt' => $data,
                    'conversationId' => $id->getId(),
                    'commentId' => $newComment->getId(),
                ]);
            } else if ($request->request->get('right') === '2') {
                return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/profile/parts/api_addConversationCommentRight.html.twig", [
                    'status' => 'ok',
                    'user' => $this->getUser(),
                    'content' => $request->request->get('Content'),
                    'CreatedAt' => $data,
                    'conversationId' => $id->getId(),
                    'commentId' => $newComment->getId(),
                ]);
            } else if ($request->request->get('right') === '3') {
                return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/profile/parts/api_addConversationCommentRightInside.html.twig", [
                    'status' => 'ok',
                    'user' => $this->getUser(),
                    'content' => $request->request->get('Content'),
                    'CreatedAt' => $data,
                    'conversationId' => $id->getId(),
                    'commentId' => $newComment->getId(),
                ]);
            } else if ($request->request->get('right') === '4') {
                return $this->render($_SERVER['DEFAULT_TEMPLATE'] . "/profile/parts/api_addConversationCommentRightInsideRight.html.twig", [
                    'status' => 'ok',
                    'user' => $this->getUser(),
                    'content' => $request->request->get('Content'),
                    'CreatedAt' => $data,
                    'conversationId' => $id->getId(),
                    'commentId' => $newComment->getId(),
                ]);
            }
        }else{
            return new Response('<span class="answer-wait-10">Wait at least 10 seconds until next answer</span>');
        }
    }

    /**
     * @Route("/api/getMoreComments/{id}", name="api_app_getMoreComments")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $id
     * @param SocialPostCommentRepository $socialPostCommentRepository
     * @param CacheManager $cm
     * @return Response
     */
    public function getMoreComments(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                    CacheManager $cm):Response{
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');


        if ($this->isCsrfTokenValid('getMoreComments', $token)){

            $comments = $socialPostCommentRepository ->findCommentsBySomething('10', 'likes', 3, $pagination, $id);

            $commentary = [];
            if ($comments == [])
            {
                return new Response('Empty content');
            }
            /** @var SocialPostComment $comment */
            $i=0;
            foreach($comments as $comment)
            {

                $commentConversationComments = $socialPostCommentRepository->findNewestCommentConversation(3, $comment->getId());

                $commentary[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'Author'=>$comment-> getAuthor()->getId(),
                    'AuthorUsername'=>$comment->getAuthor()->getUsername(),
                    'AuthorFirstName'=>$comment->getAuthor()->getFirstName(),
                    'AuthorLastName'=>$comment->getAuthor()->getLastName(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[],
                    'VisibleName' => $comment->getAuthor()->getVisibleName(),
                ];
                foreach ($commentConversationComments as $conversationComment){
                    array_push($commentary[$i]['CommentConversation'], [
                        'Id'=>$conversationComment -> getId(),
                        'Content'=>$conversationComment-> getContent(),
                        'Author'=>$conversationComment-> getAuthor()->getId(),
                        'AuthorUsername'=>$conversationComment->getAuthor()->getUsername(),
                        'AuthorFirstName'=>$conversationComment->getAuthor()->getFirstName(),
                        'AuthorLastName'=>$conversationComment->getAuthor()->getLastName(),
                        'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$conversationComment->getAuthor()->getUsername().'/'.$conversationComment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                        'createdAt'=>$conversationComment->getCreatedAt() ? $conversationComment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                        'modifiedAt'=>$conversationComment->getModifiedAt() ? $conversationComment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                        'VisibleName' => $conversationComment->getAuthor()->getVisibleName()
                    ]);
                }
                $i++;
            }

            return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_getMoreCommentsToPost.html.twig", [
                'Comments' => $commentary
            ]);
        }else{
            //TODO: bad csrf
            return new Response('Empty content');
        }
    }

    /**
     * @Route("/api/getMoreCommentsToCommentRightInside/{id}", name="api_app_getMoreCommentsToCommentRightInside")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $id
     * @param SocialPostCommentRepository $socialPostCommentRepository
     * @param CacheManager $cm
     */
    public function getMoreCommentsToCommentRightInside(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                             CacheManager $cm): Response
    {
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');


        if ($this->isCsrfTokenValid('getMoreComments', $token)){

            $comments = $socialPostCommentRepository ->findCommentConversationBySomething('10', 'likes', 3, $pagination, $id);

            $commentary = [];
            if ($comments == [])
            {
                return new Response('Empty content');
            }

            /** @var SocialPostComment $comment */
            $i=0;
            foreach($comments as $comment)
            {
                $count = $socialPostCommentRepository->countCommentsInConversation($comment->getId());

                $commentary[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'user'=>$comment->getAuthor(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[],
                    'VisibleName' => $comment->getAuthor()->getVisibleName(),
                    'count' => $count,
                    'conversationId' => $id
                ];
                $i++;
            }

            return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_getMoreCommentsToCommentRightInside.html.twig", [
                'Comments' => $commentary
            ]);

        }else{
            //TODO: bad csrf
            return new Response('Empty content');
        }
    }

    /**
     * @Route("/api/getMoreCommentsToComment/{id}", name="api_app_getMoreCommentsToComment")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $id
     * @param SocialPostCommentRepository $socialPostCommentRepository
     * @param CacheManager $cm
     */
    public function getMoreCommentsToComment(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                    CacheManager $cm){
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');


        if ($this->isCsrfTokenValid('getMoreComments', $token)){

            $comments = $socialPostCommentRepository ->findCommentConversationBySomething('10', 'likes', 3, $pagination, $id);

            $commentary = [];
            if ($comments == [])
            {
                return new Response('Empty content');
            }

            /** @var SocialPostComment $comment */
            $i=0;
            foreach($comments as $comment)
            {
                $count = $socialPostCommentRepository->countCommentsInConversation($comment->getId());

                $commentary[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'user'=>$comment->getAuthor(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[],
                    'VisibleName' => $comment->getAuthor()->getVisibleName(),
                    'count' => $count
                ];
                $i++;
            }

            if ($request->request->get('main') == "true"){
                return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_getMoreConversationComments.html.twig", [
                    'Comments' => $commentary
                ]);
            }else{
                return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_addConversationCommentRightInside.html.twig", [
                    'Comments' => $commentary
                ]);
            }

        }else{
            //TODO: bad csrf
            return new Response('Empty content');
        }
    }

    /**
     * @Route("/api/getCommentsToCommentRightInsideRight/{id}", name="api_app_getCommentsToCommentRightInsideRight")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $id
     * @param SocialPostCommentRepository $socialPostCommentRepository
     * @param CacheManager $cm
     */
    public function getCommentsToCommentRightInsideRight(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                             CacheManager $cm){
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');


        if ($this->isCsrfTokenValid('comments', $token)){

            $comments = $socialPostCommentRepository ->findCommentConversationBySomething('10', 'likes', 3, $pagination, $id);

            $commentary = [];
            if ($comments == [])
            {
                return new Response('Empty content');
            }

            /** @var SocialPostComment $comment */
            $i=0;
            foreach($comments as $comment)
            {
                $count = $socialPostCommentRepository->countCommentsInConversation($comment->getId());
                $commentConversationComments = $socialPostCommentRepository->findNewestCommentConversation(3, $comment->getId());

                $commentary[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'user'=>$comment->getAuthor(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[],
                    'VisibleName' => $comment->getAuthor()->getVisibleName(),
                    'count' => $count
                ];
                foreach ($commentConversationComments as $conversationComment){
                    array_push($commentary[$i]['CommentConversation'], [
                        'Id'=>$conversationComment -> getId(),
                        'Content'=>$conversationComment-> getContent(),
                        'Author'=>$conversationComment-> getAuthor()->getId(),
                        'AuthorUsername'=>$conversationComment->getAuthor()->getUsername(),
                        'AuthorFirstName'=>$conversationComment->getAuthor()->getFirstName(),
                        'AuthorLastName'=>$conversationComment->getAuthor()->getLastName(),
                        'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$conversationComment->getAuthor()->getUsername().'/'.$conversationComment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                        'createdAt'=>$conversationComment->getCreatedAt() ? $conversationComment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                        'modifiedAt'=>$conversationComment->getModifiedAt() ? $conversationComment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                        'VisibleName' => $conversationComment->getAuthor()->getVisibleName()
                    ]);
                }
                $i++;
            }


            return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_getConversationCommentRightInsideRight.html.twig", [
                'Comments' => $commentary,
                'ConversationId' => $id
            ]);


        }else{
            //TODO: bad csrf
            return new Response('Empty content');
        }
    }

    /**
     * @Route("/api/getMoreConversationComments/{id}", name="api_app_getMoreConversationComments")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param $id
     * @param SocialPostCommentRepository $socialPostCommentRepository
     * @param CacheManager $cm
     */
    public function getMoreConversationComments(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                                CacheManager $cm){
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');

        if ($this->isCsrfTokenValid('getMoreCommentsRight', $token)){

            $comments = $socialPostCommentRepository ->findCommentConversationBySomething('10', 'likes', 0, $pagination, $id);


            $commentary = [];
            if ($comments == [])
            {
                return new Response('No content', 204);
            }
            $i=0;
            foreach($comments as $comment)
            {

                $commentConversationComments = array_reverse($socialPostCommentRepository->findNewestCommentConversation(6, $comment->getId()));
                $counts = $socialPostCommentRepository->countCommentsInConversation($comment->getId());

                $commentary[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'Author'=>$comment-> getAuthor()->getId(),
                    'AuthorUsername'=>$comment->getAuthor()->getUsername(),
                    'AuthorFirstName'=>$comment->getAuthor()->getFirstName(),
                    'AuthorLastName'=>$comment->getAuthor()->getLastName(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[],
                    'VisibleName' => $comment->getAuthor()->getVisibleName(),
                    'Counts'=> $counts
                ];
                foreach ($commentConversationComments as $conversationComment){
                    $count = $socialPostCommentRepository->countCommentsInConversation($conversationComment->getId());
                    array_push($commentary[$i]['CommentConversation'], [
                        'Id'=>$conversationComment -> getId(),
                        'Content'=>$conversationComment-> getContent(),
                        'Author'=>$conversationComment-> getAuthor()->getId(),
                        'AuthorUsername'=>$conversationComment->getAuthor()->getUsername(),
                        'AuthorFirstName'=>$conversationComment->getAuthor()->getFirstName(),
                        'AuthorLastName'=>$conversationComment->getAuthor()->getLastName(),
                        'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$conversationComment->getAuthor()->getUsername().'/'.$conversationComment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                        'createdAt'=>$conversationComment->getCreatedAt() ? $conversationComment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                        'modifiedAt'=>$conversationComment->getModifiedAt() ? $conversationComment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                        'VisibleName' => $conversationComment->getAuthor()->getVisibleName(),
                        'conversationId' => $id
                    ]);
                }
                $i++;
            }

            return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/parts/api_getCommentsRight.html.twig", [
                'Comments' => $commentary
            ]);

        }else{
            //TODO: bad csrf
            die('Bad CSRF');
            return new Response('bad csrf', 204);
        }
    }

    /**
     * @Route("/getSocialPostLookout", name="api_app_social_post_Lookaout")
     */
    public function getSocialPostLookout(Request $request, MarkdownParserInterface $markdownParser){
        if ($this->isCsrfTokenValid('lookout', $request->request->get('csrf_token'))) {
            return new Response($markdownParser->transformMarkdown($request->get('content')));
        }else{
            echo 'bad CSRF token!';
        }
    }
}
