<?php

namespace App\Tests\Controller\Admin\Talk;

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
            uri: '/admin/talks',
            parameters: [
                'name' => 'Talk 1',
            ]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/conferences/editions?limit=1',
        );

        $this->assertResponseIsSuccessful();

        $conferenceEditionsResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $conferenceEditionsResponse);

        $conferenceEditions = $conferenceEditionsResponse['data'];

        $this->assertCount(1, $conferenceEditions);

        $conferenceEdition = $conferenceEditionsResponse['data'][0];

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/talks',
            parameters: [
                'name' => 'Talk 1',
                'youtubeId' => 'PLc2rvfiptPSR3K2Rd1Zd3Kv3ZQf5Zc6j',
                'conferenceEdition' => $conferenceEdition['id'],
                'date' => '2025-01-01',
            ]
        );

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_GET, '/talks');
        $this->assertResponseIsSuccessful();
    }
}
