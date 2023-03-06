<?php

namespace App\Controller;

use App\Form\MySearchType;
use App\Repository\BookAdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function searchBar(
        ?array $mySearch, 
    )
    {
        $form = $this->createForm(MySearchType::class,
        $mySearch,
        options: [
            'method' => 'GET',
            'action' => $this->generateUrl('app_search'),
            'attr' => ['class' => 'd-flex']
        ]
        );

        return $this->render('search/searchbar.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function index(
        Request $request,
        BookAdRepository $bookAdRepository,
        PaginatorInterface $paginator,
    ): Response
    {
        $userId = $this->getUser()->getId();
        $latitude = $this->getUser()->getLatitude();
        $longitude = $this->getUser()->getLongitude();

        $form = $this->createForm(MySearchType::class,options: [
            'method' => 'GET',
            'attr' => ['class' => 'd-flex']
        ]);
    
        $form->handleRequest($request);
        $search = $form->getData();

        $pagination = $paginator->paginate(
            $bookAdRepository->searchQueryBuilder(
                $latitude,
                $longitude,
                $search['q'] ?? '',
                $userId
            ),
            $request->query->getInt('page', 1),
            limit: 10
        );

        return $this->render('search/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

}
