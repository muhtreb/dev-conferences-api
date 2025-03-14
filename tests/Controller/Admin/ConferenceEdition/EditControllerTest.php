<?php

namespace Controller\Admin\ConferenceEdition;

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
            uri: '/admin/conferences/editions/00000000-0000-0000-0000-000000000000',
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
            uri: '/conferences/editions?withConference=true&limit=1',
        );

        $this->assertResponseIsSuccessful();

        $conferenceEditionsResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $conferenceEditionsResponse);

        $existingConferenceEdition = $conferenceEditionsResponse['data'][0];

        $newName = 'Nouveau nom de conférence';
        $client->jsonRequest(
            method: Request::METHOD_PUT,
            uri: '/admin/conferences/editions/' . $existingConferenceEdition['id'],
            parameters: [
                'name' => $newName,
                'startDate' => new \DateTime($existingConferenceEdition['startDate'])->format('Y-m-d'),
                'endDate' => new \DateTime($existingConferenceEdition['endDate'])->format('Y-m-d'),
            ],
        );

        $this->assertResponseIsSuccessful();

        $updatedConference = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($newName, $updatedConference['name']);
        $this->assertEquals($updatedConference['slug'], 'nouveau-nom-de-conference');
    }
}
