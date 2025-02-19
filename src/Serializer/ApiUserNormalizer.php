<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Security\AdminApiUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiUserNormalizer implements NormalizerInterface
{
    /**
     * @param AdminApiUser $user
     */
    public function normalize($user, ?string $format = null, array $context = []): array
    {
        return [
            'id' => 'admin_api_user',
            'email' => 'admin_api_user@gmail.com',
            'isAdmin' => true,
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof AdminApiUser;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            AdminApiUser::class => true,
        ];
    }
}
