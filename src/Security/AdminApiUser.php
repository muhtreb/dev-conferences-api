<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class AdminApiUser implements UserInterface
{
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return 'admin_api_user';
    }
}
