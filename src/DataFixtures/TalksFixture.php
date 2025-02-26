<?php

namespace App\DataFixtures;

use App\Entity\ConferenceEdition;
use App\Entity\Speaker;
use App\Entity\SpeakerTalk;
use App\Entity\Talk;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TalksFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 100; ++$i) {
            $talk = (new Talk())
                ->setName('Talk '.$i)
                ->setSlug('talk-'.$i)
                ->setConferenceEdition($this->getReference('conference_edition_'.random_int(1, 10), ConferenceEdition::class))
                ->setDate(new \DateTime())
                ->setYoutubeId('youtube_id_'.$i)
                ->setPosition($i)
                ->setDescription('Description '.$i);

            $speakerTalk = (new SpeakerTalk())
                ->setSpeaker($this->getReference('speaker_'.random_int(1, 5), Speaker::class))
                ->setTalk($talk)
                ->setMain(true);

            $manager->persist($talk);
            $manager->persist($speakerTalk);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpeakersFixture::class,
            ConferenceEditionsFixture::class,
        ];
    }
}
