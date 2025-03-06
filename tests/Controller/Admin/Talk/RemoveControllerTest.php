<?php

namespace Controller\Admin\Talk;

use App\Tests\Controller\Admin\AdminAuthenticatedClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveControllerTest extends WebTestCase
{
    use AdminAuthenticatedClientTrait;

    public function testRemoveWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->jsonRequest(
            method: Request::METHOD_DELETE,
            uri: '/admin/talks/00000000-0000-0000-0000-000000000000'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testRemoveWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/talks',
        );

        $this->assertResponseIsSuccessful();

        $talksResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $talksResponse);

        $existingtalk = $talksResponse['data'][0];

        $client->jsonRequest(
            method: Request::METHOD_DELETE,
            uri: '/admin/talks/' . $existingtalk['id'],
        );

        $this->assertResponseIsSuccessful();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/talks/' . $existingtalk['id'],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
