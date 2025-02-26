<?php

namespace App\Tests\Controller\Admin\Speaker;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CreateControllerTest extends WebTestCase
{
    public function testCreateWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/admin/speakers', content: json_encode([
            'firstName' => 'Julien',
            'lastName' => 'HUMBERT',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createClient();

        $client->request(
            method: Request::METHOD_POST,
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

        $client->request(Request::METHOD_GET, '/speakers/slug/julien-humbert');
        $this->assertResponseIsSuccessful();
    }
}
