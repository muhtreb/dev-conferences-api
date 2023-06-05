<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AdminAccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private ApiTokenRepository $repository,
    )
    {
    }

    public function getUserBadgeFrom(string $token): UserBadge
    {
        $accessToken = $this->repository->findOneBy(['token' => $token]);
        if (null === $accessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge('admin_api_user');
    }
}
