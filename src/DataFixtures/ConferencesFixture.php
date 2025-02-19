<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConferencesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $conference = (new Conference())
                ->setName('Conference '.$i)
                ->setSlug('conference-'.$i)
                ->setDescription('Description '.$i);
            $manager->persist($conference);

            $this->setReference('conference_'.$i, $conference);
        }
        $manager->flush();
    }
}
