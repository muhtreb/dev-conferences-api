<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApiTokensFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $apiToken = (new ApiToken())
            ->setToken('token');

        $manager->persist($apiToken);
        $manager->flush();
    }
}