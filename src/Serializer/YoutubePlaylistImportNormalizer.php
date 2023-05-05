<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\YoutubePlaylistImport;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class YoutubePlaylistImportNormalizer implements NormalizerInterface
{
    /**
     * @param YoutubePlaylistImport $youtubePlaylistImport
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($youtubePlaylistImport, string $format = null, array $context = []): array
    {
        return [
            'id' => $youtubePlaylistImport->getId(),
            'playlistId' => $youtubePlaylistImport->getPlaylistId(),
            'status' => $youtubePlaylistImport->getStatus(),
            'conferenceEdition' => [
                'id' => $youtubePlaylistImport->getConferenceEdition()->getId(),
                'name' => $youtubePlaylistImport->getConferenceEdition()->getName(),
            ],
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof YoutubePlaylistImport;
    }
}
