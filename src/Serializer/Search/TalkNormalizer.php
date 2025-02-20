<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\TalkDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalkNormalizer implements NormalizerInterface
{
    /**
     * @param TalkDomainObject $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'objectID' => $data->objectID,
            'name' => $data->name,
            'description' => $data->description,
            'date' => $data->date,
            'editionName' => $data->editionName,
            'speakers' => $data->speakers,
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
