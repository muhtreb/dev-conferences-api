<?php

namespace App\Tests\Controller\Speaker;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class IndexControllerTest extends WebTestCase
{
    public function testGetSpeakers(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/speakers');

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);

        $speakers = $response['data'];

        $this->assertCount(5, $speakers);
    }
}
