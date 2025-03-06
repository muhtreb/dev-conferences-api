<?php

namespace App\Tests\Controller\Admin\Tag;

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
        $client->jsonRequest(Request::METHOD_POST, '/admin/tags', parameters: [
            'name' => 'PHP',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/tags',
            parameters: [
                'name' => 'New Tag',
            ],
        );

        $this->assertResponseIsSuccessful();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/tags',
        );

        $this->assertResponseIsSuccessful();

        $tagsResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertContains('New Tag', array_column($tagsResponse['data'], 'name'));
    }

    public function testCreateExistingTagWithAuthentication(): void
    {
        $client = static::createAdminAuthenticatedClient();

        $client->jsonRequest(
            method: Request::METHOD_GET,
            uri: '/tags'
        );

        $this->assertResponseIsSuccessful();

        $tagsResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $tagsResponse);

        $existingTag = $tagsResponse['data'][0];

        $client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/admin/tags',
            parameters: [
                'name' => $existingTag['name'],
            ],
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('violations', $response);
        $this->assertCount(1, $response['violations']);
        $this->assertEquals('name', $response['violations'][0]['propertyPath']);
        $this->assertEquals('Cette valeur est déjà utilisée', $response['violations'][0]['title']);
    }
}
