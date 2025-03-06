<?php

namespace App\Tests\Controller\Tag;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/tags');

        $this->assertResponseIsSuccessful();

        $tagsResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $tagsResponse);
        $this->assertCount(10, $tagsResponse['data']);
    }
}
