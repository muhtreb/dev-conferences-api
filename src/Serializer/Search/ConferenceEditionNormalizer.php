<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Indexation\ConferenceEditionDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceEditionNormalizer implements NormalizerInterface
{
    /**
     * @param ConferenceEditionDomainObject $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'objectID' => $data->objectID,
            'name' => $data->name,
            'description' => $data->description,
            'date' => $data->date,
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceEditionDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ConferenceEditionDomainObject::class => true,
        ];
    }
}
