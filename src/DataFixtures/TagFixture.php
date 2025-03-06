<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['PHP', 'Javascript', 'Java', 'Python', 'Ruby', 'C++', 'C#', 'Go', 'Swift', 'Kotlin'] as $tag) {
            $manager->persist((new Tag())->setName($tag));
        }

        $manager->flush();
    }
}
