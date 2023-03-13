<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'sendMailToValidateAccount', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'sendMailToValidateAccount', entity: User::class )]
class UserNewAccountSendMailListener
{
    public function __construct()
    {
    }

    public function sendMailToValidateAccount(User $user): void
    {

    }
}
