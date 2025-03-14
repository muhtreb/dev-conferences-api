<?php

namespace Controller\Admin\Conference;

use App\Tests\Controller\Admin\AdminAuthenticatedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditControllerTest extends WebTestCase
{
    use AdminAuthenticatedClientTrait;

    public function testEditWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/conferences/00000000-0000-0000-0000-000000000000',
            parameters: [
                'name' => 'Conference 6',
            ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testEditWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/conferences',
        );

        $this->assertResponseIsSuccessful();

        $conferencesResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $conferencesResponse);

        $existingConference = $conferencesResponse['data'][0];

        $newName = 'Nouveau nom de conférence';
        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/conferences/' . $existingConference['id'],
            parameters: [
                'name' => $newName,
            ],
        );

        $this->assertResponseIsSuccessful();

        $updatedConference = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($newName, $updatedConference['name']);
        $this->assertEquals($updatedConference['slug'], 'nouveau-nom-de-conference');
    }
}
