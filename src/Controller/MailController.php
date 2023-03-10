<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function sendMail(MailerInterface $mailer): void
    {
        $mail = (new TemplatedEmail())
            ->from('expediteur@demo.test')
            ->to('destinataire@demo.test')
            ->subject('Mon beau sujet')
            ->htmlTemplate('mail/template.html.twig')
            ->context([
                'name' => 'Bobby',
            ])
        ;

        $mailer->send($mail);
    }
}
