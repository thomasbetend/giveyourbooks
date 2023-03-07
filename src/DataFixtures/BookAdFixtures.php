<?php

namespace App\DataFixtures;

use App\Entity\BookAd;
use App\Entity\BookAdImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookAdFixtures extends Fixture implements DependentFixtureInterface
{
    public const GUEPARD = 'GUEPARD';
    public const MISERABLES = 'MISERABLES';
    public const SUR_LA_ROUTE = 'SUR_LA_ROUTE';

    public function load(ObjectManager $manager): void
    {
        $bookAd1 = new BookAd();
        $bookAd1->setTitle('Le Guépard');
        $bookAd1->setCategory($this->getReference(CategoryFixtures::CATEGORY_POCHE));
        $bookAd1->setDescription('Donne livre en parfait état. L\'intrigue est magnifique');
        $bookAd1->setImagePath('le-guepard-1.jpeg');
        $bookAd1->setUser($this->getReference(UserFixtures::USER_ADMIN));
        $manager->persist($bookAd1);
        $this->addReference(self::GUEPARD, $bookAd1);

        $bookAd2 = new BookAd();
        $bookAd2->setTitle('les misérables');
        $bookAd2->setCategory($this->getReference(CategoryFixtures::CATEGORY_POCHE));
        $bookAd2->setDescription('Livre qui a un vécu, une histoire');
        $bookAd2->setImagePath('les-miserables-1.jpeg');
        $bookAd2->setUser($this->getReference(UserFixtures::USER_THOMAS));
        $manager->persist($bookAd2);
        $this->addReference(self::MISERABLES, $bookAd2);

        $bookAd3 = new BookAd();
        $bookAd3->setTitle('Sur la route');
        $bookAd3->setCategory($this->getReference(CategoryFixtures::CATEGORY_POCHE));
        $bookAd3->setDescription('Un road movie fascinant');
        $bookAd3->setImagePath('sur-la-route.jpeg');
        $bookAd3->setUser($this->getReference(UserFixtures::USER_SIMONE));
        $manager->persist($bookAd3);
        $this->addReference(self::SUR_LA_ROUTE, $bookAd3);

        for ($i = 1; $i <=50; $i++) {
            $bookAd = new BookAd();
            $bookAd->setTitle($i . '. les misérables');
            $bookAd->setCategory($this->getReference(CategoryFixtures::CATEGORY_POCHE));
            $bookAd->setBook($this->getReference(BookFixtures::BOOK_2));
            $bookAd->setDescription('Magnifique bouquin qui a déclenché un véritable tollé');
            $bookAd->setImagePath('les-miserables-1.jpeg');
            $bookAd->setUser($this->getReference(UserFixtures::USER_GINA));
            $manager->persist($bookAd);
            $this->addReference($i . '-les misérables', $bookAd);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookAdImageFixtures::class,
            BookFixtures::class,
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
