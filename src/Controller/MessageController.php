<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessageController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/all_messages_read', name: 'app_all_message')]
    public function areAllMessagesRead(
        MessageRepository $messageRepository,
        #[CurrentUser] ?User $user
    ): JsonResponse
    {
        $messagesNotRead = $messageRepository->findBy([
            'user_destination' => $user,
            'seenByUserDestination' => false,
        ]);

        if ($messagesNotRead) {
            $areAllMessagesRead = false;
        } else {
            $areAllMessagesRead = true;
        }

        return new JsonResponse([
            'areAllMessagesRead' => $areAllMessagesRead,
            'totalMessages' => count($messagesNotRead),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/messages_in_conversation_read/{id}', name: 'app_message_in_conversation')]
    public function areMessagesInConversationRead(
        Conversation $conversation,
        MessageRepository $messageRepository,
        #[CurrentUser] ?User $user
    ): JsonResponse
    {
        if (!in_array($user, $conversation->getUser()->toArray())) {
            return new JsonResponse([
                'erreur' => 'Vous n\'avez pas les droits'
            ]);
        }

        $messagesNotRead = $messageRepository->findBy([
            'conversation' => $conversation,
            'user_destination' => $this->getUSer(),
            'seenByUserDestination' => false,
        ]);

        if ($messagesNotRead) {
            $arelMessagesInConversationRead = false;
        } else {
            $arelMessagesInConversationRead = true;
        }

        return new JsonResponse([
            'arelMessagesInConversationRead' => $arelMessagesInConversationRead,
            'totalMessagesNotReadInConversation' => count($messagesNotRead),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new_message_in_conversation/{id}', name: 'app_new_message')]
    public function newMessagesInConversation(
        Conversation $conversation,
        MessageRepository $messageRepository,
        #[CurrentUser] ?User $user
    ): JsonResponse
    {
        if (!in_array($user, $conversation->getUser()->toArray())) {
            return new JsonResponse([
                'erreur' => 'Vous n\'avez pas les droits'
            ]);
        }

        $newMessages = $messageRepository->findBy([
            'conversation' => $conversation,
            'user_destination' => $user,
            'seenByUserDestination' => false,
        ]);


        $newMessagesToPass = [];

        foreach ($newMessages as $newMessage) {
            $newMessagesToPass[] = [$newMessage->getContent(), $newMessage->getCreatedAt()];
        }

        return new JsonResponse([
            'newMessagesToPass' => $newMessagesToPass,
        ]);
    }
}
