<?php

namespace App\Tests\Controller\Admin\Conference;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends WebTestCase
{
    public function testCreateWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('POST', '/admin/conferences', content: json_encode([
            'name' => 'Conference 6',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createClient();

        $client->request(
            method: 'POST',
            uri: '/admin/conferences',
            server: [
                'HTTP_Authorization' => 'Bearer token',
            ],
            content: json_encode([
                'name' => 'Conference 6',
            ])
        );

        $this->assertResponseIsSuccessful();
    }
}
