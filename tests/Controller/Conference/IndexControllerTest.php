<?php

namespace App\Tests\Controller\Conference;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/conferences');

        $this->assertResponseIsSuccessful();

        $conferences = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $conferences);
        $this->assertArrayHasKey('meta', $conferences);
        $this->assertArrayHasKey('name', $conferences['data'][0]);
    }
}
