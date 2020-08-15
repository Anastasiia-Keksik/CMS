<?php

namespace App\Controller\API;

use App\Entity\SocialPostComment;
use App\Form\ChoseMenuCategoryFormType;
use App\Form\MakeNewRouteFormType;
use App\Repository\SocialPostRepository;
use App\Repository\SocialPost;
use App\Repository\SocialPostCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\DependencyInjection\Configuration;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManagerAwareInterface;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Liip\ImagineBundle\Service\FilterService;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class SocialController
 * @package App\Controller\API
 */
class SocialController extends AbstractController
{
    /**
     * @Route("/api/get_social_posts", name="api_app_getSocialPosts")
     * @IsGranted("ROLE_USER")
     */
    public function showUsers(LoggerInterface $logger, Request $request, SocialPostRepository $socialPostRepository, SocialPostCommentRepository $underCommentsRepo)
    {
       //TODO: check if user who sees it (pobiera to) have dostep do tego (not black listed, friend etc)
        $id = $request->request->get('profile');
        //$id = $request->query->get('profile');

        $socialPosts = $socialPostRepository->loadNewPosts($id);
        
        $posts = [];
        $postsIt=0;

        foreach ($socialPosts as $post){
            if($post->getSoftDelete()==false)            {
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
                    'BGFilename' => $post->getBackgroundFilename()
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
                            'CommentConversation'=>[]
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
                                        'modifiedAt'=>$underComment->getModifiedAt()
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
            return new JsonResponse(['status'=>"empty"]);
        }
        return $this->render($_SERVER['DEFAULT_TEMPLATE']."/profile/api_loadPost.html.twig",[
            'posts'=>$posts
        ]);
    }

    /**
     * @Route("/addCommentToPost/{id}", name="api_app_addComment")
     * @IsGranted("ROLE_USER")
     */
    public function addComment(Request $request, \App\Entity\SocialPost $id, EntityManagerInterface $em){
        $token = $request->request->get('_token');

        if ($request->request->get('Content') == ""){
            return new JsonResponse([
                'status'=>'empty content'
            ]);
        }

        $data = new \DateTime('now');

        if ($this->isCsrfTokenValid('comment', $token)){
            $newComment = new SocialPostComment();
            $newComment -> setAuthor($this->getUser());
            $newComment -> setContent($request->request->get('Content'));
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
        return new JsonResponse([
            'status'=>'ok',
            'Author'=>$this->getUser()->getUsername(),
            'Content'=>$request->request->get('Content'),
            'CreatedAt'=> $data,
            'ConversationId'=>null,
            'commentId'=>$newComment->getId(),
        ]);
    }

    /**
     * @Route("/getMoreComments/{id}", name="api_app_getMoreComments")
     * @IsGranted("ROLE_USER")
     */
    public function getMoreComments(Request $request, $id, SocialPostCommentRepository $socialPostCommentRepository,
                                    CacheManager $cm){
        $token = $request->request->get('_token');
        $pagination = $request->request->get('pagination');

        if ($this->isCsrfTokenValid('getMoreComments', $token)){

            $comments = $socialPostCommentRepository ->findCommentsBySomething('10', 'likes', 3, $pagination, $id);

            $i=0;
            foreach($comments as $comment)
            {

                $commentConversationComments = $socialPostCommentRepository->findNewestCommentConversation(3, $comment->getId());

                $commentresult[$i]=[
                    'Id'=>$comment -> getId(),
                    'Content'=>$comment-> getContent(),
                    'Author'=>$comment-> getAuthor()->getId(),
                    'AuthorUsername'=>$comment->getAuthor()->getUsername(),
                    'AuthorFirstName'=>$comment->getAuthor()->getFirstName(),
                    'AuthorLastName'=>$comment->getAuthor()->getLastName(),
                    'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$comment->getAuthor()->getUsername().'/'.$comment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                    'createdAt'=>$comment->getCreatedAt() ? $comment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                    'modifiedAt'=>$comment->getModifiedAt() ? $comment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    'CommentConversation'=>[]
                ];
                foreach ($commentConversationComments as $conversationComment){
                    array_push($commentresult[$i]['CommentConversation'], [
                        'Id'=>$conversationComment -> getId(),
                        'Content'=>$conversationComment-> getContent(),
                        'Author'=>$conversationComment-> getAuthor()->getId(),
                        'AuthorUsername'=>$conversationComment->getAuthor()->getUsername(),
                        'AuthorFirstName'=>$conversationComment->getAuthor()->getFirstName(),
                        'AuthorLastName'=>$conversationComment->getAuthor()->getLastName(),
                        'AuthorAvatarFileUrl'=>$cm->getBrowserPath('/upload/avatars/'.$conversationComment->getAuthor()->getUsername().'/'.$conversationComment->getAuthor()->getAvatarFileName(), 'my_thumb'),
                        'createdAt'=>$conversationComment->getCreatedAt() ? $conversationComment->getCreatedAt()->format('Y-m-d H:i:s') : "",
                        'modifiedAt'=>$conversationComment->getModifiedAt() ? $conversationComment->getModifiedAt()->format('Y-m-d H:i:s') : "",
                    ]);
                }
                $i++;
            }

            return new JsonResponse([
                'status'=>'ok',
                'comments'=>$commentresult
            ]);
        }else{
            //TODO: bad csrf
        }
    }
}
