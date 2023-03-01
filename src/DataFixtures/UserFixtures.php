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
        $this->addReference(self::USER_2, $thomas);
        $manager->persist($thomas);

        $manager->flush();
    }
}
