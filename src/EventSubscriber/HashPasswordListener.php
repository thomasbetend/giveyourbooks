<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'hashPassword', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'hashPassword', entity: User::class )]
class HashPasswordListener
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hashPassword(User $user): void
    {
        if (!$user->plainPassword){
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->plainPassword
        );

        $user->setPassword($hashedPassword);
    }
}
