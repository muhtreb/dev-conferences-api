<?php

namespace App\Tests\Controller\Talk;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testGetSpeakers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/talks');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);

        $talks = $response['data'];
        $this->assertCount(10, $talks);

        $this->assertEquals(1, $response['meta']['page']);
        $this->assertEquals(10, $response['meta']['nbPages']);
        $this->assertEquals(2, $response['meta']['nextPage']);
        $this->assertNull($response['meta']['prevPage']);
        $this->assertEquals(100, $response['meta']['nbHits']);
    }
}
