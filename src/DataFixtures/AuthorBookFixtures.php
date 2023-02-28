<?php

namespace App\DataFixtures;

use App\Entity\AuthorBook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorBookFixtures extends Fixture
{
    public const AUTHOR_BOOK_1 = 'AUTHOR_BOOK_1';
    public const AUTHOR_BOOK_2 = 'AUTHOR_BOOK_2';

    public function load(ObjectManager $manager): void
    {
        $authorBook1 = new AuthorBook();
        $manager->persist($authorBook1);
        $this->addReference(self::AUTHOR_BOOK_1, $authorBook1);

        $authorBook2 = new AuthorBook();
        $manager->persist($authorBook2);
        $this->addReference(self::AUTHOR_BOOK_2, $authorBook2);

        $manager->flush();
    }
}
