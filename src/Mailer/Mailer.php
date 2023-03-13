<?php

namespace App\Mailer;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class Mailer 
{

    public function __construct(
        private MailerInterface $mailerInterface, 
        private TokenGeneratorInterface $tokenGeneratorInterface,
        private EntityManagerInterface $em
    )
    {
    }

    public function sendResetPasswordEmail(
        User $user,
        string $from, 
        string $to,
        string $subject,
        string $template,
        ): Response
    {

        $token = $this->tokenGeneratorInterface->generateToken();
        $user->setResetToken($token);
        $this->em->persist($user);
        $this->em->flush();

        $url = $this->generateUrl('reset_password', ['token' => $token],
        UrlGeneratorInterface::ABSOLUTE_URL);

        $mail = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'url' => $url,
                'user' => $user,
            ])
        ;

        $this->mailerInterface->send($mail);

        return $this->render('security/email_reset_password_sent.html.twig', [
            'user' => $user,
        ]);
    }

    public function sendAccountVerifEmail(
        User $user,
        string $from, 
        string $to,
        string $subject,
        string $template,
        string $url
        ): Response
    {

        $token = $this->tokenGeneratorInterface->generateToken();
        $user->setValidationAccountToken($token);
        $this->em->persist($user);
        $this->em->flush();

        $url = $this->generateUrl('validation_account', ['token' => $token],
        UrlGeneratorInterface::ABSOLUTE_URL);

        $mail = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'url' => $url,
                'user' => $user,
            ])
        ;

        $this->mailerInterface->send($mail);

        return $this->render('security/email_reset_password_sent.html.twig', [
            'user' => $user,
        ]);
    }
}