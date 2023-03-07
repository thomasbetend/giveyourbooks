<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    const CONVERSATION_1 = 'CONVERSATION_1';
    const CONVERSATION_2 = 'CONVERSATION_2';

    public function load(ObjectManager $manager): void
    {
        $conversation1 = new Conversation();
        $conversation1->setName('conversation1');
        $conversation1->addUser($this->getReference(UserFixtures::USER_SIMONE));
        $conversation1->addUser($this->getReference(UserFixtures::USER_GINA));
        $conversation1->setBookAd($this->getReference(BookAdFixtures::SUR_LA_ROUTE));
        $manager->persist($conversation1);
        $this->addReference(self::CONVERSATION_1, $conversation1);

        $conversation2 = new Conversation();
        $conversation2->setName('conversation2');
        $conversation2->addUser($this->getReference(UserFixtures::USER_THOMAS));
        $conversation2->addUser($this->getReference(UserFixtures::USER_SIMONE));
        $conversation2->setBookAd($this->getReference(BookAdFixtures::MISERABLES));
        $manager->persist($conversation2);
        $this->addReference(self::CONVERSATION_2, $conversation2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BookAdFixtures::class,
        ];
    }
}
