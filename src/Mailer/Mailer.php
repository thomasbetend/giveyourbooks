<?php

namespace App\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer 
{

    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(
        string $from, 
        string $to,
        string $subject,
        array $context
        ): void
    {

        $mail = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('mail/template.html.twig')
            ->context($context)
        ;

        $this->mailer->send($mail);
    }
}