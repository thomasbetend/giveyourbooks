<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
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

    #[Route('/mesconversations/{id<\d+>}', name: 'app_my_conversation')]
    public function myConversation(
        int $id,
        ConversationRepository $conversationRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $conversationRepository->getMessageByConversationQueryBuilder($id),
            $request->query->getInt('page', 1),
            limit: 10
        );

        return $this->render('conversation/show.html.twig', [
            'pagination' => $pagination,
        ]);
    }

}
