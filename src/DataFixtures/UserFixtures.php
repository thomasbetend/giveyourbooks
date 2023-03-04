<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_1 = 'USER_1';
    public const USER_2 = 'USER_2';
    public const USER_3 = 'USER_3';

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
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setAddress('195 rue des pyrénées 75020 paris');
        $admin->setLatitude(48.861472);
        $admin->setLongitude(2.400091);
        $admin->setCity('paris');
        $this->addReference(self::USER_1, $admin);
        $manager->persist($admin);

        $thomas = new User();
        $thomas->setPseudo('thom');
        $thomas->setEmail('thomas@giveyourbooks.com');
        $hashedPassword2 = $this->passwordHasher->hashPassword(
            $thomas,
            'demo01',
        );
        $thomas->setPassword($hashedPassword2);
        $thomas->setRoles(['ROLE_USER']);
        $thomas->setAddress('6 rue de la Croix-Blanche 78870 bailly');
        $thomas->setLatitude(48.842961);
        $thomas->setLongitude(2.075309);
        $thomas->setCity('bailly');
        $this->addReference(self::USER_2, $thomas);
        $manager->persist($thomas);

        $simone = new User();
        $simone->setPseudo('Sim');
        $simone->setEmail('simone@giveyourbooks.com');
        $hashedPassword3 = $this->passwordHasher->hashPassword(
            $simone,
            'demo01',
        );
        $simone->setPassword($hashedPassword3);
        $simone->setRoles(['ROLE_USER']);
        $simone->setAddress('noisy-le-roi');
        $simone->setLatitude(48.842961);
        $simone->setLongitude(2.075309);
        $simone->setCity('noisy-le-roi');
        $this->addReference(self::USER_3, $simone);
        $manager->persist($simone);

        $manager->flush();
    }
}
