<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\ConferenceEditionDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceEditionNormalizer implements NormalizerInterface
{
    /**
     * @param ConferenceEditionDomainObject $object
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
            'date' => $object->date,
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceEditionDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ConferenceEditionDomainObject::class];
    }
}
