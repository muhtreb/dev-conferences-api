<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\SpeakerDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SpeakerNormalizer implements NormalizerInterface
{
    /**
     * @param SpeakerDomainObject $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'objectID' => $object->objectID,
            'firstName' => $object->firstName,
            'lastName' => $object->lastName,
            'description' => $object->description,
            'githubUsername' => $object->githubUsername,
            'xUsername' => $object->xUsername,
            'countTalks' => $object->countTalks
        ];
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof SpeakerDomainObject;
    }
}
