<?php

namespace App\Controller;

use App\Entity\ChatConversation;
use App\Entity\ChatMessage;
use App\Entity\ChatParticipant;
use App\Repository\ChatMessageRepository;
use App\Repository\ChatParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/messages", name="messages.")
 */
class ChatMessageController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ChatMessageRepository
     */
    private $messageRepository;
    /**
     * @var ParticipantRepository
     */
    private $participantRepository;
    /**
     * @var PublisherInterface
     */
    private $publisher;

    public function __construct(EntityManagerInterface $entityManager,
                                ChatMessageRepository $messageRepository,
                                ChatParticipantRepository $participantRepository,
                                PublisherInterface $publisher)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->participantRepository = $participantRepository;
        $this->publisher = $publisher;
    }

    /**
     * @Route("/{id}", name="getMessages", methods={"GET"})
     * @param Request $request
     * @param ChatConversation $conversation
     * @return Response
     */
    public function getMessages(Request $request, ChatConversation $conversation)
    {
        // can i view the conversation

        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = array_reverse($this->messageRepository->findMessageByConversationId($conversation->getId()));

        /**
         * @var $message ChatMessage
         */
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
            );
        }, $messages);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("getMoreMsgs/{id}"), name="getMoreMsgs")
     * @param ChatConversation $conversation
     * @return Response
     */
    public function getMoreMsgs(Request $request, ChatConversation $conversation){

        $messages = array_reverse($this->messageRepository->findMessagesPagination($conversation->getId(), $request->get('page')));

        /**
         * @var $message ChatMessage
         */
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
            );
        }, $messages);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @Route("/{conversation}", name="newMessage", methods={"POST"})
     * @param Request $request
     * @param ChatConversation $conversation
     * @return JsonResponse
     * @throws \Exception
     */
    public function newMessage(Request $request, ChatConversation $conversation, SerializerInterface $serializer)
    {
        $user = $this->getUser();

        $recipients = $this->participantRepository->findParticipantByConverstionIdAndUserId(
            $conversation->getId(),
            $user->getId()
        );

        if (count($recipients)===1){
            /** @var ChatParticipant $recipients */
            if ($recipients[0]->getActiveStatus() === 0){
                return new JsonResponse(['error' => 'The only one recipient has left the conversation. Message not sent.']);
            }
        }

        if($conversation->getActive()==0){
            $conversation->setActive(1);
        }

        //dd($recipient);

        if (strlen($request->request->get('Content')) > 256){
            $content = substr($request->request->get('Content'), 0, 256);
        } else {
            $content = $request->request->get('Content');
        }

        $message = new ChatMessage();
        $message->setContent($content);
        $message->setCreatedAt(new \DateTime());
        $message->setUser($user);
        $message->setMine(true);

        $conversation->addChatMessage($message);
        $conversation->setLastMessageId($message);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($message);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
        $message->setMine(false);
//        $messageSerialized = $serializer->serialize($message, 'json', [
//            'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
//        ]);
        $messageSerialized = $serializer->serialize(['id'=>$message->getId(), 'content'=>$message->getContent(),
            'createdAt'=>$message->getCreatedAt(), 'authorid'=>$message->getUser()->getId(),
            'conversation' => $message->getConversation()->getId()], 'json');
        $update = new Update(
            [
                sprintf("/conversations/%s", $conversation->getId()),
//                sprintf("/pies"),
                sprintf("/conversations/%s", $this->getUser()->getUsername()),
            ],
            $messageSerialized,
            false
        );

        $this->publisher->__invoke($update);

        return new JsonResponse($messageSerialized);

//        return $this->json($message, Response::HTTP_CREATED, [], [
//            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
//        ]);
    }
}
