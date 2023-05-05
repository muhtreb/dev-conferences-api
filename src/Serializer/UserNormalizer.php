<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        protected Security $security
    )
    {
    }

    /**
     * @param User $user
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($user, string $format = null, array $context = []): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'isAdmin' => $this->security->isGranted('ROLE_ADMIN', $user),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }
}
