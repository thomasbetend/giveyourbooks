<?php

namespace App\Controller;

use App\Entity\BookAd;
use App\Form\BookAdType;
use App\Repository\BookAdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/dons')]
class BookAdController extends AbstractController
{
    #[Route('/', name: 'app_book_ad_index', methods: ['GET'])]
    public function index(BookAdRepository $bookAdRepository): Response
    {
        return $this->render('book_ad/index.html.twig', [
            'book_ads' => $bookAdRepository->findAll(),
        ]);
    }

    #[Route('/publier', name: 'app_book_ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookAdRepository $bookAdRepository): Response
    {
        $bookAd = new BookAd();
        $form = $this->createForm(BookAdType::class, $bookAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookAd->setUser($this->getUser());
            $bookAd->setLatitude($this->getUser()->getLatitude());
            $bookAd->setLongitude($this->getUser()->getLongitude());
            $bookAdRepository->save($bookAd, true);
            
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_ad/new.html.twig', [
            'book_ad' => $bookAd,
            'form' => $form,
        ]);
    }

    #[Route('/monespace', name: 'app_book_ad_mine', methods: ['GET', 'POST'])]
    public function myspace(BookAdRepository $bookAdRepository): Response
    {
        $userId = $this->getUser()->getId();

        return $this->render('book_ad/myspace.html.twig', [
            'bookAds' => $bookAdRepository->findByUser($userId),
        ]);
    }

    #[Route('/aroundme', name: 'app_book_around_me', methods: ['GET', 'POST'])]
    public function around(BookAdRepository $bookAdRepository): Response
    {
        $userLat = $this->getUser()->getLatitude();
        $userLng = $this->getUser()->getLongitude();

        $bookAds = $bookAdRepository->findByDist($userLat, $userLng, 1);

        //dd($bookAds);

        return $this->render('book_ad/aroundme.html.twig', [
            'bookAds' => $bookAds,
        ]);
    }

    #[Route('/{id}', name: 'app_book_ad_show', methods: ['GET'])]
    public function show(BookAd $bookAd): Response
    {
        return $this->render('book_ad/show.html.twig', [
            'book_ad' => $bookAd,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_ad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BookAd $bookAd, BookAdRepository $bookAdRepository): Response
    {
        $form = $this->createForm(BookAdType::class, $bookAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookAdRepository->save($bookAd, true);

            return $this->redirectToRoute('app_book_ad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book_ad/edit.html.twig', [
            'book_ad' => $bookAd,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_ad_delete', methods: ['POST'])]
    public function delete(Request $request, BookAd $bookAd, BookAdRepository $bookAdRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookAd->getId(), $request->request->get('_token'))) {
            $bookAdRepository->remove($bookAd, true);
        }

        return $this->redirectToRoute('app_book_ad_index', [], Response::HTTP_SEE_OTHER);
    }
}
