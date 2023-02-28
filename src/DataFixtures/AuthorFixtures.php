<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $author1 = new Author();
        $author1->setLastname('Di Lampedusa');
        $author1->setFirstname('Tomasi');
        $author1->addAuthorBook($this->getReference(AuthorBookFixtures::AUTHOR_BOOK_1));
        $manager->persist($author1);

        $author2 = new Author();
        $author2->setLastname('Hugo');
        $author2->setFirstname('Victor');
        $author2->addAuthorBook($this->getReference(AuthorBookFixtures::AUTHOR_BOOK_2));
        $manager->persist($author2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuthorBookFixtures::class,
        ];
    }
}
