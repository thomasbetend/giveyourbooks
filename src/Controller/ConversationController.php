<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConversationController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/mesconversations', name: 'app_my_conversations')]
    public function myConversations(
        ConversationRepository $conversationRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $userId = $this->getUser()->getId();

        $pagination = $paginator->paginate(
            $conversationRepository->getUserConversationQueryBuilder($userId),
            $request->query->getInt('page', 1),
            limit: 10
        );

        return $this->render('conversation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/mesconversations/{id<\d+>}', name: 'app_my_conversation')]
    public function myConversation(
        Conversation $conversation,
        MessageRepository $messageRepository,
        PaginatorInterface $paginator,
        Request $request,
        HubInterface $hub,
        #[CurrentUser] ?User $user
    ): Response
    {
        if (!in_array($user, $conversation->getUser()->toArray())) {
            return $this->redirectToRoute('app_home');
        }

        $message = new Message();

        foreach ($conversation->getUser() as $userConv) {
            if ($userConv != $user) {
                $userDestination = $userConv;
            }
        }

        $messagesSeenByUser = $messageRepository->findBy([
            'user' => $userDestination,
            'user_destination' => $user,
            'seenByUserDestination' => false,
        ]);

        foreach ($messagesSeenByUser as $messageSeen) {
            $messageSeen->setSeenByUserDestination(true);
            $messageRepository->save($messageSeen, true);
        }

        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setContent($form->getData()["contentNew"]);
            $message->setUser($user);
            $message->setUserDestination($userDestination);
            $message->setSeenByUserDestination(false);
            $message->setConversation($conversation);

            $messageRepository->save($message, true);

            $update = new Update(
                'https://giveyourboox.com/message',
                json_encode([
                    'content' => 'message',
                    'userId' => $user->getId(),
                    'conversationId' => $conversation->getId(),
                    'userDestinationId' => $userDestination->getId(),
                ])
            );
    
            $hub->publish($update);
    
            dd($hub);
            
            return $this->redirectToRoute('app_my_conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        $pagination = $paginator->paginate(
            $messageRepository->getMessageByConversationIdQueryBuilder($conversation->getId()),
            $request->query->getInt('page', 1),
            limit: 20
        );

        return $this->render('conversation/show.html.twig', [
            'pagination' => $pagination,
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

}
