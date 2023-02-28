<?php

namespace App\Controller;

use App\Repository\BookAdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'controller_name' => 'HomeController',
        ]);
    }
}
