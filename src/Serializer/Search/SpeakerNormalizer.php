<?php

declare(strict_types=1);

namespace App\Serializer\Search;

use App\DomainObject\Search\SpeakerDomainObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SpeakerNormalizer implements NormalizerInterface
{
    /**
     * @param SpeakerDomainObject $data
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        return [
            'objectID' => $data->objectID,
            'firstName' => $data->firstName,
            'lastName' => $data->lastName,
            'description' => $data->description,
            'githubUsername' => $data->githubUsername,
            'xUsername' => $data->xUsername,
            'mastodonUsername' => $data->mastodonUsername,
            'blueskyUsername' => $data->blueskyUsername,
            'speakerDeckUsername' => $data->speakerDeckUsername,
            'countTalks' => $data->countTalks,
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof SpeakerDomainObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            SpeakerDomainObject::class => true,
        ];
    }
}
