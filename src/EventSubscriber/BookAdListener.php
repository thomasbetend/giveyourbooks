<?php

namespace App\EventSubscriber;

use App\Entity\BookAd;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsEntityListener(event: Events::prePersist, method: 'geocode', entity: BookAd::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'geocode', entity: BookAd::class )]
class BookAdListener
{
    public function __construct()
    {
    }

    public function geocode(BookAd $bookAd): void
    {
    }
}
