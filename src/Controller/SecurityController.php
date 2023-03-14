<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordRequestType;
use App\Form\ResetAddressType;
use App\Form\ResetPasswordType;
use App\Form\ResetPseudoType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
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
        MailerInterface $mailer
    ): Response
    {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail([$form->getData()["email"]]);

            if ($user) {
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('reset_password', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);

                //dd($url);
                $mail = (new TemplatedEmail())
                    ->from('reinitialisation@giveyourbooks.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de mot de passe')
                    ->htmlTemplate('mail/password_reset.html.twig')
                    ->context([
                        'url' => $url,
                        'user' => $user,
                    ])
                ;

                $mailer->send($mail);

                return $this->render('security/email_reset_password_sent.html.twig', [
                    'user' => $user,
                ]);
            }


            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route(path: 'oubli-password/{token}', name: 'reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $userRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->getData()["password"]
                    )
                );

                $userRepository->save($user, true);

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'form' => $form,
            ]);
        }
        
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: 'validation-compte/{token}', name: 'validation_account')]
    public function validateAccount(
        string $token,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response
    {
        $user = $userRepository->findOneBy(['validation_account_token' => $token]);

        //dd($user);

        if ($user) {
            $user->setIsVerified(true);
            $user->setValidationAccountToken('');
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre compte est validé');
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('danger', 'Jeton invalide');
        return new Response('app_register');
    }

    #[Route('/mesinfos', name: 'app_myinfos')]
    public function showUserInfos(
        #[CurrentUser] ?User $user
    ): Response
    {
        return $this->render('user/infos.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/mesinfos/editer/{id}', name: 'app_myinfos_edit', methods: ['GET', 'POST'])]
    public function editUserInfos(
        BookAd $bookAd,
        BookAdRepository $bookAdRepository,
        Request $request,
        #[CurrentUser] ?User $user
    ): Response
    {
        $form = $this->createForm(BookAdType::class, $bookAd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookAdRepository->save($bookAd, true);
            
            return $this->redirectToRoute('app_my_book_ad', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('my_book_ad/edit.html.twig', [
            'book_ad' => $bookAd,
            'form' => $form,
        ]);
    }

    #[Route('/mesinfos/change-password', name: 'app_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        #[CurrentUser] ?User $user,
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPassword = $form->getData()["password"];
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $newPassword,
            );

            $user->setPassword($hashedPassword);

            $userRepository->save($user, true);

            $this->addFlash('success', 'Mot de passe changé avec succès');
            
            return $this->redirectToRoute('app_myinfos');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/mesinfos/change-pseudo', name: 'app_change_pseudo', methods: ['GET', 'POST'])]
    public function changePseudo(
        #[CurrentUser] ?User $user,
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        $form = $this->createForm(ResetPseudoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPseudo = $form->getData()["pseudo"];

            $user->setPseudo($newPseudo);

            $userRepository->save($user, true);

            $this->addFlash('success', 'Pseudo changé avec succès');
            
            return $this->redirectToRoute('app_myinfos');
        }

        return $this->render('user/change_pseudo.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/mesinfos/change-address', name: 'app_change_address', methods: ['GET', 'POST'])]
    public function changeAddress(
        #[CurrentUser] ?User $user,
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        $form = $this->createForm(ResetAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newAddress = $form->getData()["address"];

            $user->setAddress($newAddress);

            $userRepository->save($user, true);

            $this->addFlash('success', 'Adresse changée avec succès');
            
            return $this->redirectToRoute('app_myinfos');
        }

        return $this->render('user/change_address.html.twig', [
            'form' => $form,
        ]);
    }
}
