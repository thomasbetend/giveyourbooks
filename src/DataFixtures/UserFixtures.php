<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'USER_ADMIN';
    public const USER_THOMAS = 'USER_THOMAS';
    public const USER_SIMONE = 'USER_SIMONE';
    public const USER_GINA = 'USER_GINA';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setPseudo('admin');
        $admin->setEmail('admin@giveyourbooks.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'demo01',
        );
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAddress('195 rue des pyrénées 75020 paris');
        $admin->setCity('paris');
        $this->addReference(self::USER_ADMIN, $admin);
        $manager->persist($admin);

        $thomas = new User();
        $thomas->setPseudo('thom');
        $thomas->setEmail('thomas@giveyourbooks.com');
        $hashedPassword2 = $this->passwordHasher->hashPassword(
            $thomas,
            'demo01',
        );
        $thomas->setPassword($hashedPassword2);
        $thomas->setAddress('6 rue de la Croix-Blanche 78870 bailly');
        $this->addReference(self::USER_THOMAS, $thomas);
        $manager->persist($thomas);

        $simone = new User();
        $simone->setPseudo('Sim');
        $simone->setEmail('simone@giveyourbooks.com');
        $hashedPassword3 = $this->passwordHasher->hashPassword(
            $simone,
            'demo01',
        );
        $simone->setPassword($hashedPassword3);
        $simone->setAddress('noisy-le-roi');
        $this->addReference(self::USER_SIMONE, $simone);
        $manager->persist($simone);

        $gina = new User();
        $gina->setPseudo('Gin');
        $gina->setEmail('gina@giveyourbooks.com');
        $hashedPassword4 = $this->passwordHasher->hashPassword(
            $gina,
            'demo01',
        );
        $gina->setPassword($hashedPassword4);
        $gina->setAddress('Parc de l\'Etoile 67100 Strasbourg');
        $this->addReference(self::USER_GINA, $gina);
        $manager->persist($gina);

        $manager->flush();
    }
}
