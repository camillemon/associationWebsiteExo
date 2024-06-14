<?php

namespace App\DataFixtures;

use App\Entity\Events;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $event1 = new Events();
        $event1->setDate(new \DateTime('2025-06-20'));
        $event1->setCity('Paris');
        $event1->setLocation('Place de la République');
        $manager->persist($event1);

        $event2 = new Events();
        $event2->setDate(new \DateTime('2025-07-05'));
        $event2->setCity('Lyon');
        $event2->setLocation('Place des Terreaux');
        $manager->persist($event2);

        $event3 = new Events();
        $event3->setDate(new \DateTime('2025-08-01'));
        $event3->setCity('Marseille');
        $event3->setLocation('Vieux port');
        $manager->persist($event3);


        // Sauvegarder les événements en base de données
        $manager->flush();
    }
}
