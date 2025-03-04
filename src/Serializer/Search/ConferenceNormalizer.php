<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Indexation\ConferenceDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceNormalizer implements NormalizerInterface
{
    /**
     * @param ConferenceDomainObject $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'objectID' => $data->objectID,
            'name' => $data->name,
            'description' => $data->description,
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ConferenceDomainObject::class => true,
        ];
    }
}
