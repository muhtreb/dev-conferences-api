<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Entity\ConferenceEdition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConferenceEditionsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $conferenceEdition = (new ConferenceEdition())
                ->setName('Conference Edition '.$i)
                ->setSlug('conference-edition-'.$i)
                ->setStartDate(new \DateTime())
                ->setConference($this->getReference('conference_'.random_int(1, 5), Conference::class))
                ->setDescription('Description '.$i);

            $this->setReference('conference_edition_'.$i, $conferenceEdition);
            $manager->persist($conferenceEdition);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ConferencesFixture::class,
        ];
    }
}
