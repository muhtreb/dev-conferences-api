<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\TalkDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalkNormalizer implements NormalizerInterface
{
    /**
     * @param TalkDomainObject $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'objectID' => $object->objectID,
            'name' => $object->name,
            'description' => $object->description,
            'date' => $object->date,
            'editionName' => $object->editionName,
            'speakers' => $object->speakers,
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof TalkDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            TalkDomainObject::class => true,
        ];
    }
}
