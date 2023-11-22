<?php

namespace App\Tests\Controller\ConferenceEdition;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences/editions');

        $this->assertResponseIsSuccessful();

        $conferenceEditions = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $conferenceEditions[0]);
    }
}
