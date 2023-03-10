<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\GovGeocoder\GovGeocoder;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'geocode', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'geocode', entity: User::class )]
class UserGeocodeListener
{

    public function __construct(private GovGeocoder $govGeocoder)
    {
    }

    public function geocode(User $user): void
    {
        if ($user->geocoded) {
            throw new \RuntimeException();
        }

        $coords = $this->govGeocoder->geocodeAddress($user->getAddress());

        $user->geocoded = true;
        $user->setLatitude($coords['geometry']['coordinates'][1]);
        $user->setLongitude($coords['geometry']['coordinates'][0]);
        $user->setCity($coords['properties']['city']);

        //dd($user);

    }
}
