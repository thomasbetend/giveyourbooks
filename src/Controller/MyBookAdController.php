<?php

namespace App\Controller;

use App\Entity\BookAd;
use App\Entity\User;
use App\Form\BookAdType;
use App\Repository\BookAdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MyBookAdController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
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

        //dd($bookAdRepository->getUserBookAdsQueryBuilder($user));

        return $this->render('my_book_ad/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/donner', name: 'app_my_book_ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookAdRepository $bookAdRepository): Response
    {
        $bookAd = new BookAd();
        $form = $this->createForm(BookAdType::class, $bookAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookAd->setUser($this->getUser());
            $bookAdRepository->save($bookAd, true);
            
            return $this->redirectToRoute('app_my_book_ad', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('my_book_ad/new.html.twig', [
            'book_ad' => $bookAd,
            'form' => $form,
        ]);
    }

    #[Route('/monespace/supprimer/{slug>}', name: 'app_my_book_ad_delete', methods: ['GET', 'POST'])]
    #[IsGranted('BOOK_AD_OWNER', 'bookAd')]
    public function delete(
        BookAd $bookAd,
        BookAdRepository $bookAdRepository
    ): Response
    {
        $bookAdRepository->remove($bookAd, true);

        $this->addFlash(
            'success',
            'L\'annonce '.$bookAd->getTitle().' a bien étée supprimé'
        );

        return $this->redirectToRoute('app_my_book_ad');
    }

    #[Route('/monespace/editer/{slug}', name: 'app_my_book_ad_edit', methods: ['GET', 'POST'])]
    #[IsGranted('BOOK_AD_OWNER', 'bookAd')]
    public function edit(
        BookAd $bookAd,
        BookAdRepository $bookAdRepository,
        Request $request,
    ): Response
    {
        $form = $this->createForm(BookAdType::class, $bookAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookAdRepository->save($bookAd, true);
            
            return $this->redirectToRoute('app_my_book_ad', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('my_book_ad/new.html.twig', [
            'book_ad' => $bookAd,
            'form' => $form,
        ]);
    }
}
