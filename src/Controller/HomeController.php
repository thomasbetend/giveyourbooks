<?php

namespace App\Controller;

use App\Repository\BookAdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        PaginatorInterface $paginator,
        BookAdRepository $bookAdRepository,
        Request $request,
        ): Response
    {
            if($this->getUser()) {
                $latitude = $this->getUser()->getLatitude();
                $longitude = $this->getUser()->getLongitude();
    
                $pagination = $paginator->paginate(
                    $bookAdRepository->geocodeQueryBuilder($latitude, $longitude),
                    $request->query->getInt('page', 1),
                    limit: 10
                );

                //dd($pagination);
        
                return $this->render('home/aroundme.html.twig', [
                    'pagination' => $pagination,
                ]);
    
            } else {
                $bookAds = $bookAdRepository->findRecent(10);
    
                return $this->render('home/index.html.twig', [
                    'bookAds' => $bookAds,
                ]);
            }
            
        
    }



}
