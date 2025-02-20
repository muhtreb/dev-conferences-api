<?php

namespace Controller\ConferenceEdition;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences/editions');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);

        $conferenceEditions = $response['data'];
        $this->assertCount(10, $conferenceEditions);
        $this->assertArrayHasKey('name', $conferenceEditions[0]);

        $this->assertEquals(1, $response['meta']['page']);
        $this->assertEquals(1, $response['meta']['nbPages']);
        $this->assertNull($response['meta']['nextPage']);
        $this->assertNull($response['meta']['prevPage']);
        $this->assertEquals(10, $response['meta']['nbHits']);
    }
}
