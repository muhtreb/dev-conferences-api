<?php

namespace App\Tests\Controller\Admin\Conference;

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
        $client->jsonRequest(Request::METHOD_POST, '/admin/conferences', parameters: [
            'name' => 'Conference 6',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/conferences',
            parameters: [
                'name' => 'Conference 6',
            ],
        );

        $this->assertResponseIsSuccessful();
    }
}
