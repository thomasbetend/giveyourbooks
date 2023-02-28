<?php

namespace App\DataFixtures;

use App\Entity\BookAd;
use App\Entity\BookAdImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookAdFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $bookAd1 = new BookAd();
        $bookAd1->setTitle('Livre Le Guépard');
        $bookAd1->setPlace('versailles');
        $bookAd1->setBook($this->getReference(BookFixtures::BOOK_1));
        $bookAd1->setDescription('Donne livre en parfait état. L\'intrigue est magnifique');
        $bookAd1->addImage($this->getReference(BookAdImageFixtures::BOOK_AD_IMAGE_1));
        $manager->persist($bookAd1);

        $bookAd2 = new BookAd();
        $bookAd2->setTitle('Donne les misérables');
        $bookAd2->setPlace('taverny');
        $bookAd2->setBook($this->getReference(BookFixtures::BOOK_2));
        $bookAd2->setDescription('Livre qui a un vécu, une histoire');
        $bookAd2->addImage($this->getReference(BookAdImageFixtures::BOOK_AD_IMAGE_2));
        $manager->persist($bookAd2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookAdImageFixtures::class,
            BookFixtures::class,
        ];
    }
}
