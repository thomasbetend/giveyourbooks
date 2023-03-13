<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Mailer\Mailer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[AsEntityListener(event: Events::prePersist, method: 'sendMailToValidateAccount', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'sendMailToValidateAccount', entity: User::class )]
class UserNewAccountSendMailListener
{
    public function __construct(
        private MailerInterface $mailerInterface, 
        private TokenGeneratorInterface $tokenGeneratorInterface,
        private EntityManagerInterface $em,
        private RouterInterface $routerInterface
        )
    {
    }

    public function sendMailToValidateAccount(User $user): void
    {
        
        if($user->getValidationAccountToken()){

            $url = $this->routerInterface->generate('validation_account', ['token' => $user->getValidationAccountToken()],
            UrlGeneratorInterface::ABSOLUTE_URL);
    
            $mail = (new TemplatedEmail())
                ->from('validation@giveyourbooks.com')
                ->to($user->getEmail())
                ->subject('Validation compte')
                ->htmlTemplate('mail/validation_account.html.twig')
                ->context([
                    'url' => $url,
                    'user' => $user,
                ])
            ;
    
            $this->mailerInterface->send($mail);
        }
    }
}
