<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookAdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MyBookAdController extends AbstractController
{

    #[Route('/monespace', name: 'app_my_book_ad')]
    public function myspace(
        BookAdRepository $bookAdRepository,
        PaginatorInterface $paginator,
        Request $request,
        ): Response
    {
        $user = $this->getUser();

        $pagination = $paginator->paginate(
            $bookAdRepository->getUserBookAdsQueryBuilder($user),
            $request->query->getInt('page', 1),
            limit: 10
        );

        return $this->render('my_book_ad/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
