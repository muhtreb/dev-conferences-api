<?php

namespace App\Tests\Controller\Conference;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences');

        $this->assertResponseIsSuccessful();

        $conferences = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('name', $conferences[0]);
    }
}
