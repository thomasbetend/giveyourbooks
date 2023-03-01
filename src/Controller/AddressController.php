<?php

namespace App\Controller;

use App\Repository\BookAdRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address/register/{lat}/{lng}', name: 'app_address')]
    public function register($lat, $lng, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $user = $userRepository->find($userId);


        if (isset($user)) {
            $user->setLatitude($lat);
            $user->setLongitude($lng);
            $em->persist($user);

            $em->flush();
        }

        return new JsonResponse([
            'latitude' => $lat,
            'longitude' => $lng,
        ]);
    }
}
