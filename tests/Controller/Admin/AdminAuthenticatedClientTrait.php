<?php

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait AdminAuthenticatedClientTrait
{
    protected function createAdminAuthenticatedClient(): KernelBrowser
    {
        return static::createClient(server: [
            'HTTP_Authorization' => 'Bearer token',
            'HTTP_Accept' => 'application/json',
        ]);
    }
}