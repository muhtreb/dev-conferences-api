<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\YoutubePlaylistImport;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class YoutubePlaylistImportNormalizer implements NormalizerInterface
{
    /**
     * @param YoutubePlaylistImport $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return [
            'id' => $data->getId(),
            'playlistId' => $data->getPlaylistId(),
            'status' => $data->getStatus(),
            'conferenceEdition' => [
                'id' => $data->getConferenceEdition()->getId(),
                'name' => $data->getConferenceEdition()->getName(),
            ],
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof YoutubePlaylistImport;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            YoutubePlaylistImport::class => true,
        ];
    }
}
