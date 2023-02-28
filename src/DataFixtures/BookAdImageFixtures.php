<?php

namespace App\DataFixtures;

use App\Entity\BookAdImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookAdImageFixtures extends Fixture
{
    public const BOOK_AD_IMAGE_1 = 'BOOK_AD_IMAGE_1';
    public const BOOK_AD_IMAGE_2 = 'BOOK_AD_IMAGE_2';

    public function load(ObjectManager $manager): void
    {
        $bookAdImage1 = new BookAdImage();
        $bookAdImage1->setPosition(1);
        $bookAdImage1->setPath('le-guepard-1.jpeg');
        $this->addReference(self::BOOK_AD_IMAGE_1, $bookAdImage1);
        $manager->persist($bookAdImage1);

        $bookAdImage2 = new BookAdImage();
        $bookAdImage2->setPosition(1);
        $bookAdImage2->setPath('les-miserables-1.jpeg');
        $this->addReference(self::BOOK_AD_IMAGE_2, $bookAdImage2);
        $manager->persist($bookAdImage2);

        $manager->flush();
    }
}
