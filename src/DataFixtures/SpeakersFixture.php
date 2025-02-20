<?php

namespace App\DataFixtures;

use App\Entity\Speaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpeakersFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $speaker = new Speaker()
                ->setFirstName('First Name '.$i)
                ->setLastName('Last Name '.$i)
                ->setSlug('first-name-'.$i.'-last-name-'.$i.'-'.$i)
                ->setDescription('Description '.$i);

            $this->setReference('speaker_'.$i, $speaker);

            $manager->persist($speaker);
        }
        $manager->flush();
    }
}
