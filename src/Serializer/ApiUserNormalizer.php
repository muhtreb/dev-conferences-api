<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Security\AdminApiUser;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiUserNormalizer implements NormalizerInterface
{
    /**
     * @param AdminApiUser $user
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($user, string $format = null, array $context = []): array
    {
        return [
            'id' => 'admin_api_user',
            'email' => 'admin_api_user@gmail.com',
            'isAdmin' => true,
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof AdminApiUser;
    }
}
