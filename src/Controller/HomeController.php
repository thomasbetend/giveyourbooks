<?php

namespace App\Controller;

use App\Entity\BookAd;
use App\Entity\User;
use App\Repository\BookAdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        PaginatorInterface $paginator,
        BookAdRepository $bookAdRepository,
        Request $request,
        #[CurrentUser] ?User $user
        ): Response
    {
        if($user) {
            $userId = $user->getId();
            $latitude = $user->getLatitude();
            $longitude = $user->getLongitude();

            $pagination = $paginator->paginate(
                $bookAdRepository->geocodeQueryBuilder($latitude, $longitude, $userId),
                $request->query->getInt('page', 1),
                limit: 9
            );
    
            return $this->render('home/aroundme.html.twig', [
                'pagination' => $pagination,
            ]);

        } else {
            $bookAds = $bookAdRepository->findRecent(3);

            return $this->render('home/index.html.twig', [
                'bookAds' => $bookAds,
            ]);
        }
    }

}
