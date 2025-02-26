<?php

namespace App\Tests\Controller\Admin\Speaker;

use App\Tests\Controller\Admin\AdminAuthenticatedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends WebTestCase
{
    use AdminAuthenticatedClientTrait;

    public function testCreateWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/speakers',
            parameters: [
                'firstName' => 'Julien',
                'lastName' => 'HUMBERT',
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/speakers',
            parameters: [
                'firstName' => 'Julien',
                'lastName' => 'HUMBERT',
            ]
        );

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/speakers/slug/julien-humbert');
        $this->assertResponseIsSuccessful();
    }
}
