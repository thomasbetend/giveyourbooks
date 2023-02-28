<?php

namespace App\DataFixtures;

use App\Entity\Book;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const BOOK_1 = 'BOOK_1';
    public const BOOK_2 = 'BOOK_2';

    public function load(ObjectManager $manager): void
    {
        $book1 = new Book();
        $book1->setTitle('Le Guépard');
        $book1->addAuthorBook($this->getReference(AuthorBookFixtures::AUTHOR_BOOK_1));
        $book1->setPurchaseDate(DateTime::createFromFormat("d-m-Y", "01-09-2022"));
        $this->addReference(self::BOOK_1, $book1);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setTitle('Les misérables');
        $book2->addAuthorBook($this->getReference(AuthorBookFixtures::AUTHOR_BOOK_2));
        $book2->setPurchaseDate(DateTime::createFromFormat("d-m-Y", "14-12-2022"));
        $this->addReference(self::BOOK_2, $book2);
        $manager->persist($book2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuthorBookFixtures::class,
        ];
    }
}
