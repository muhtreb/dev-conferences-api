<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\ConferenceEdition;
use App\Repository\TalkRepository;
use App\Repository\YoutubePlaylistImportRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceEditionNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected TalkRepository $talkRepository,
        protected YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
    ) {
    }

    /**
     * @param ConferenceEdition $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $startDate = $data->getStartDate();
        $endDate = $data->getEndDate();
        $data = [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'slug' => $data->getSlug(),
            'description' => $data->getDescription(),
            'startDate' => $startDate ? $startDate->format('Y-m-d H:i:s') : null,
            'endDate' => $endDate ? $endDate->format('Y-m-d H:i:s') : null,
            'thumbnailImageUrl' => $data->getConference()->getThumbnailImageUrl(),
        ];

        if ($withCountTalks = $context['withCountTalks'] ?? true) {
            $data['countTalks'] = $this->talkRepository->count(['conferenceEdition' => $data]);
        }

        if ($withConference = $context['withConference'] ?? false) {
            $data['conference'] = $this->normalizer->normalize($data->getConference(), null, [
                'withEditions' => false,
            ]);
        }

        if ($withTalks = $context['withTalks'] ?? false) {
            $talks = $this->talkRepository->findBy(['conferenceEdition' => $data], ['position' => 'ASC']);
            $data['talks'] = $this->normalizer->normalize($talks);
        }

        if ($withPlaylists = $context['withPlaylists'] ?? false) {
            $data['playlists'] = [];
            $playlists = $this->youtubePlaylistImportRepository->findBy([
                'conferenceEdition' => $data,
                'status' => 'success',
            ]);
            foreach ($playlists as $playlist) {
                $data['playlists'][] = [
                    'id' => $playlist->getPlaylistId(),
                    'status' => $playlist->getStatus(),
                ];
            }
        }

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceEdition;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ConferenceEdition::class => true,
        ];
    }
}
