<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminApiUserProvider implements UserProviderInterface
{
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new AdminApiUser();
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return new AdminApiUser();
    }

    public function supportsClass(string $class): bool
    {
        return AdminApiUser::class === $class;
    }
}