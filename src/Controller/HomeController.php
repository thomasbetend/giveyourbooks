<?php

namespace App\Controller;

use App\Entity\BookAd;
use App\Form\BookAd2Type;
use App\Repository\BookAdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookAdRepository $bookAdRepository): Response
    {
        $bookAds = $bookAdRepository->findAll();

        return $this->render('home/index.html.twig', [
            'bookAds' => $bookAds,
        ]);
    }

    #[Route('/{lat}/{lng}', name: 'app_book_search_by_coord', methods: ['GET', 'POST'])]
    public function searchByCoord(float $lat, float $lng, BookAdRepository $bookAdRepository): Response
    {
        $bookAdsAround = $bookAdRepository->findByCoord($lat, $lng);

        return $this->render('book_ad/myspace.html.twig', [
            'bookAds' => $bookAdsAround,
        ]);
    }

}
