<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/all_messages_read', name: 'app_message')]
    public function areAllMessagesRead(
        MessageRepository $messageRepository
    ): JsonResponse
    {
        $messagesNotRead = $messageRepository->findBy([
            'user_destination' => $this->getUser(),
            'seenByUserDestination' => false,
        ]);

        if ($messagesNotRead) {
            $areAllMessagesRead = false;
        } else {
            $areAllMessagesRead = true;
        }

        return new JsonResponse([
            'areAllMessagesRead' => $areAllMessagesRead,
        ]);
    }

    #[Route('/messages_in_conversation_read/{id}', name: 'app_message')]
    public function areMessagesInConversationRead(
        Conversation $conversation,
        MessageRepository $messageRepository
    ): JsonResponse
    {
        if (!in_array($this->getUser(), $conversation->getUser()->toArray())) {
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
        ]);
    }
}
