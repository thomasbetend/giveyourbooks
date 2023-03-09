<?php

namespace App\Controller;

use App\Form\PasswordRequestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-password', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $em,
    ): Response
    {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail([$form->getData()["email"]]);

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();
                //dd($token);
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('reset_password', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);

                dd($url);
            }


            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route(path: 'oubli-password/{token}', name: 'reset_password')]
    public function resetPass(): Response
    {

    }
}
