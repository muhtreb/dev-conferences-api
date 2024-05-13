<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Tag;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TagNormalizer implements NormalizerInterface
{
    /**
     * @param Tag $tag
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($tag, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $tag->getId(),
            'name' => $tag->getName(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Tag;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Tag::class => true,
        ];
    }
}
