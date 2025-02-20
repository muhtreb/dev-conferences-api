<?php

namespace App\Tests\Controller\Admin\Speaker;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends WebTestCase
{
    public function testCreateWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('POST', '/admin/speakers', content: json_encode([
            'firstName' => 'Julien',
            'lastName' => 'HUMBERT',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/admin/speakers',
            server: [
                'HTTP_Authorization' => 'Bearer token',
            ],
            content: json_encode([
                'firstName' => 'Julien',
                'lastName' => 'HUMBERT',
            ])
        );

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/speakers/slug/julien-humbert');
        $this->assertResponseIsSuccessful();
    }
}
