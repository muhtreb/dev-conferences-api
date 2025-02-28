<?php

namespace App\Tests\Controller\Admin\ConferenceEdition;

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
        $client->jsonRequest(Request::METHOD_POST, '/admin/conferences/00000000-0000-0000-0000-000000000000/editions', parameters: [
            'name' => 'Conference 6',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        // Get the list of conferences
        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/conferences',
        );

        $this->assertResponseIsSuccessful();

        $conferencesResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $conferencesResponse);

        $existingConference = $conferencesResponse['data'][0];

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/conferences/' . $existingConference['id'] . '/editions',
            parameters: [
                'name' => 'Conference Edition Test',
            ],
        );

        $this->assertResponseIsSuccessful();

        $conferenceEdition = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Conference Edition Test', $conferenceEdition['name']);
        $this->assertEquals('conference-edition-test', $conferenceEdition['slug']);
    }
}
