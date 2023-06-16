<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\ConferenceDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceNormalizer implements NormalizerInterface
{
    /**
     * @param ConferenceDomainObject $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'objectID' => $object->objectID,
            'name' => $object->name,
            'description' => $object->description,
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ConferenceDomainObject::class];
    }
}
