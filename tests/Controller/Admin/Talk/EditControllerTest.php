<?php

namespace Controller\Admin\Talk;

use App\Tests\Controller\Admin\AdminAuthenticatedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditControllerTest extends WebTestCase
{
    use AdminAuthenticatedClientTrait;

    public function testCreateWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/talks/00000000-0000-0000-0000-000000000000',
            parameters: [
                'name' => 'New Talk name'
            ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testEditWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/talks',
        );

        $talksContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('data', $talksContent);
        $existingTalk = $talksContent['data'][0];

        $newName = 'New Talk name';

        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/talks/' . $existingTalk['id'],
            parameters: [
                'name' => $newName,
            ]
        );

        $this->assertResponseIsSuccessful();

        $updatedTalk = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($newName, $updatedTalk['name']);
    }
}
