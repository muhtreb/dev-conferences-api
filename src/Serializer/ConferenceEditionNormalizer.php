<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\ConferenceEdition;
use App\Repository\TalkRepository;
use App\Repository\YoutubePlaylistImportRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceEditionNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected TalkRepository $talkRepository,
        protected YoutubePlaylistImportRepository $youtubePlaylistImportRepository
    ) {
    }

    /**
     * @param ConferenceEdition $conferenceEdition
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($conferenceEdition, string $format = null, array $context = []): array
    {
        $startDate = $conferenceEdition->getStartDate();
        $endDate = $conferenceEdition->getEndDate();
        $data = [
            'id' => $conferenceEdition->getId(),
            'name' => $conferenceEdition->getName(),
            'slug' => $conferenceEdition->getSlug(),
            'description' => $conferenceEdition->getDescription(),
            'startDate' => $startDate ? $startDate->format('Y-m-d H:i:s') : null,
            'endDate' => $endDate ? $endDate->format('Y-m-d H:i:s') : null,
            'thumbnailImageUrl' => $conferenceEdition->getConference()->getThumbnailImageUrl(),
        ];

        if ($withCountTalks = $context['withCountTalks'] ?? true) {
            $data['countTalks'] = $this->talkRepository->count(['conferenceEdition' => $conferenceEdition]);
        }

        if ($withConference = $context['withConference'] ?? false) {
            $data['conference'] = $this->normalizer->normalize($conferenceEdition->getConference(), null, [
                'withEditions' => false,
            ]);
        }

        if ($withTalks = $context['withTalks'] ?? false) {
            $talks = $this->talkRepository->findBy(['conferenceEdition' => $conferenceEdition], ['position' => 'ASC']);
            $data['talks'] = $this->normalizer->normalize($talks);
        }

        if ($withPlaylists = $context['withPlaylists'] ?? false) {
            $data['playlists'] = [];
            $playlists = $this->youtubePlaylistImportRepository->findBy(['conferenceEdition' => $conferenceEdition]);
            foreach ($playlists as $playlist) {
                $data['playlists'][] = [
                    'id' => $playlist->getPlaylistId(),
                    'status' => $playlist->getStatus()
                ];
            }
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ConferenceEdition;
    }
}
