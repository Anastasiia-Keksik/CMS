<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\ChatConversation;
use App\Entity\ChatParticipant;
use App\Repository\ChatConversationRepository;
use App\Repository\AccountRepository;
use App\Repository\ChatParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\WebLink\Link;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * Class ChatConversationController
 * @package App\Controller
 * @Route("/conversations", name="conversations.")
 */
class ChatConversationController extends AbstractController
{
    /**
     * @var AccountRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ChatConversationRepository
     */
    private $conversationRepository;
    /**
     * @var CacheManager
     */
    private $imagineCacheManager;
    /**
     * @var ChatParticipantRepository
     */
    private $chatParticipantRepository;

    public function __construct(AccountRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                ChatConversationRepository $conversationRepository,
                                CacheManager $liipCache,
                                ChatParticipantRepository $chatParticipantRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
        $this->imagineCacheManager = $liipCache;
        $this->chatParticipantRepository = $chatParticipantRepository;
    }

    /**
     * @Route("/{otherUser}", name="setConversation", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Security("is_granted('ROLE_USER')")
     */
    public function setConversation(Request $request, $otherUser)
    {
        //$otherUser = $request->get('otherUser', 0);

        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception("The user was not found");
        }

        // cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception("That's deep but you cannot create a conversation with yourself");
        }

        // Check if conversation already exists
        $conversation = $this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {
            throw new \Exception("The conversation already exists");
        }

        $conversation = new ChatConversation();
        $conversation -> setActive(1);

        $participant = new ChatParticipant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);


        $otherParticipant = new ChatParticipant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($conversation);
            $this->entityManager->persist($participant);
            $this->entityManager->persist($otherParticipant);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }


        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    /**
     * @Route("/deactivate/{conversation}", name="deactivate-conversation")
     */
    public function deactivateConvtersation(ChatConversation $conversation, EntityManagerInterface $em)
    {
        $conversation->setActive(0);

        $em->persist($conversation);
        $em->flush();

        return new JsonResponse(['success'=>$conversation->getId() . " deactivated"]);
    }

    /**
     * @Route("/leave/{conversation}", name="deactivate-conversation")
     */
    public function leaveConvtersation(ChatConversation $conversation, EntityManagerInterface $em,
                                       ChatParticipantRepository $chatParticipantRepository, SerializerInterface $serializer,
                                       PublisherInterface $publisher)
    {
        //$chatParticipant=$chatParticipantRepository->findParticipantByConverstionIdAndUserId($conversation->getId(), $this->getUser()->getId());
        $conversation->setActive(0);
        $participants = $conversation->getChatParticipants();


        foreach ($participants as $participant)
        {
            if ($participant->getUser()->getId() == $this->getUser()->getId())
            {
                $participant=$participant->setActiveStatus(0);
            }
        }



        $messageSerialized = $serializer->serialize(['left'=>1, 'userUsername'=>$this->getUser()->getUsername(), 'userFirstName'=>$this->getUser()->getFirstName(), 'userLastName'=>$this->getUser()->getLastName(), 'timeleft'=>new \DateTime()], 'json');
        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
                sprintf("/conversations/%s", $this->getUser()->getUsername()),
            ],
            $messageSerialized,
            false
        );

        $publisher->__invoke($update);

        $em->persist($participant);
        $em->persist($conversation);
        $em->flush();

        return new JsonResponse(['success'=>$conversation->getId() . " abandoned"]);
    }

    /**
     * @Route("/", name="getConversations", methods={"GET"})
     */
    public function getConversations(Request $request)
    {
        $userid = $this->getUser()->getId();

        $conversations = $this->conversationRepository->findConversationsByUser2($userid);

        $hubUrl = $this->getParameter('mercure.default_hub');

        $i = 0;
        if ($conversations !== null){
        foreach ($conversations as $conversation) {

            $wszyscyPartycypanci[$i] = $this->chatParticipantRepository->findParticipantsByConverstionIdAndUserId($conversation['conversationId'], $userid);

            foreach ($wszyscyPartycypanci as $grupaPartycypantow) {
                $y = 0;
                foreach ($grupaPartycypantow as $partycypant) {
                    if ($partycypant['id'] === $conversation['conversationId']) {
                        $partycypant['avatarFileName'] = $this->imagineCacheManager->getBrowserPath('/upload/avatars/' . $partycypant['username'] . '/' . $partycypant['avatarFileName'], 'my_thumb');
                        unset($partycypant['id']);
                        $conversations[$i]['participants'][$y++] = $partycypant;
                    }
                }
            }

            $i = $i + 1;
        }
        }

        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversations);
    }
}
