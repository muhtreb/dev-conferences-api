<?php

declare(strict_types=1);

namespace App\Serializer;

use App\DomainObject\MetaDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MetaNormalizer implements NormalizerInterface
{
    /**
     * @param MetaDomainObject $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'page' => $data->page,
            'nbPages' => $data->nbPages,
            'nextPage' => $data->nextPage,
            'prevPage' => $data->prevPage,
            'nbHits' => $data->nbHits,
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof MetaDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MetaDomainObject::class => true,
        ];
    }
}
