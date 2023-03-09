<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $message1 = new Message();
        $message1->setConversation($this->getReference(ConversationFixtures::CONVERSATION_1));
        $message1->setUser($this->getReference(UserFixtures::USER_SIMONE));
        $message1->setUserDestination($this->getReference(UserFixtures::USER_GINA));
        $message1->setContent("Hello GINA !");
        $message1->setSeenByUserDestination(false);
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setConversation($this->getReference(ConversationFixtures::CONVERSATION_1));
        $message2->setUser($this->getReference(UserFixtures::USER_GINA));
        $message2->setUserDestination($this->getReference(UserFixtures::USER_SIMONE));
        $message2->setContent("Hello Simone !");
        $message2->setSeenByUserDestination(false);
        $manager->persist($message2);

        $message12 = new Message();
        $message12->setConversation($this->getReference(ConversationFixtures::CONVERSATION_1));
        $message12->setUser($this->getReference(UserFixtures::USER_SIMONE));
        $message12->setUserDestination($this->getReference(UserFixtures::USER_GINA));
        $message12->setContent("Ca va GINA ?");
        $message12->setSeenByUserDestination(true);
        $manager->persist($message12);

        $message22 = new Message();
        $message22->setConversation($this->getReference(ConversationFixtures::CONVERSATION_1));
        $message22->setUser($this->getReference(UserFixtures::USER_GINA));
        $message22->setUserDestination($this->getReference(UserFixtures::USER_SIMONE));
        $message22->setContent("Oui et toi Simone ?");
        $message22->setSeenByUserDestination(false);
        $manager->persist($message22);

        $message3 = new Message();
        $message3->setConversation($this->getReference(ConversationFixtures::CONVERSATION_2));
        $message3->setUser($this->getReference(UserFixtures::USER_THOMAS));
        $message3->setUserDestination($this->getReference(UserFixtures::USER_SIMONE));
        $message3->setSeenByUserDestination(true);
        $message3->setContent("Hello Simone !");
        $manager->persist($message3);

        $message4 = new Message();
        $message4->setConversation($this->getReference(ConversationFixtures::CONVERSATION_2));
        $message4->setUser($this->getReference(UserFixtures::USER_SIMONE));
        $message4->setUserDestination($this->getReference(UserFixtures::USER_THOMAS));
        $message4->setContent("Hello Thomas !");
        $message4->setSeenByUserDestination(false);
        $manager->persist($message4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UserFixtures::class,
            ConversationFixtures::class,
        ];
    }
}
