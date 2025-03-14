<?php

namespace Controller\Admin\Speaker;

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
            uri: '/admin/speakers/00000000-0000-0000-0000-000000000000',
            parameters: [
                'firstName' => 'Julien',
                'lastName' => 'HUMBERT',
            ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/speakers',
        );

        $speakersContent = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('data', $speakersContent);
        $existingSpeaker = $speakersContent['data'][0];

        $newFirstName = 'Julien';
        $newLastName = 'HUMBERT';

        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/speakers/' . $existingSpeaker['id'],
            parameters: [
                'firstName' => $newFirstName,
                'lastName' => $newLastName,
            ]
        );

        $this->assertResponseIsSuccessful();

        $updatedSpeaker = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($newFirstName, $updatedSpeaker['firstName']);
        $this->assertEquals($newLastName, $updatedSpeaker['lastName']);
    }
}
