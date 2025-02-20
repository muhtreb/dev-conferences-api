<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        protected Security $security,
    ) {
    }

    /**
     * @param User $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $data->getId(),
            'email' => $data->getEmail(),
            'isAdmin' => $this->security->isGranted('ROLE_ADMIN', $data),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
