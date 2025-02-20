<?php

namespace Controller\ConferenceEdition;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LatestControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences/editions/latest');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayNotHasKey('meta', $response);

        $conferenceEditions = $response['data'];
        $this->assertCount(10, $conferenceEditions);
        $this->assertArrayHasKey('name', $conferenceEditions[0]);
    }
}
