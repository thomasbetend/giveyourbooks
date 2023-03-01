<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_POCHE = 'CATEGORY_POCHE';
    public const CATEGORY_BROCHE = 'CATEGORY_BROCHE';
    public const CATEGORY_BD = 'CATEGORY_BD';
    public const CATEGORY_MANGA = 'CATEGORY_MANGA';


    public function load(ObjectManager $manager): void
    {
        $poche = new Category();
        $poche->setName('Livre de poche');
        $this->addReference(self::CATEGORY_POCHE, $poche);
        $manager->persist($poche);

        $bd = new Category();
        $bd->setName('BD');
        $this->addReference(self::CATEGORY_BD, $bd);
        $manager->persist($bd);

        $manga = new Category();
        $manga->setName('Manga');
        $this->addReference(self::CATEGORY_MANGA, $manga);
        $manager->persist($manga);

        $broche = new Category();
        $broche->setName('Livre broché');
        $this->addReference(self::CATEGORY_BROCHE, $broche);
        $manager->persist($broche);

        $manager->flush();
    }
}
