<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Talk;
use App\Repository\SpeakerRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalkNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected SpeakerRepository $speakerRepository
    ) {
    }

    /**
     * @param Talk $talk
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($talk, string $format = null, array $context = []): array
    {
        $data = [
            'id' => $talk->getId(),
            'name' => $talk->getName(),
            'description' => $talk->getDescription(),
            'date' => $talk->getDate()->format('Y-m-d H:i:s'),
            'youtubeId' => $talk->getYoutubeId(),
            'duration' => $talk->getDuration(),
            'images' => [
                'thumbnail' => $talk->getThumbnailImageUrl(),
                'poster' => $talk->getPosterImageUrl()
            ],
            'slug' => $talk->getSlug(),
        ];

        if ($withEdition = $context['withEdition'] ?? true) {
            $data['edition'] = $this->normalizer->normalize($talk->getConferenceEdition(), null, [
                'withTalks' => false,
            ]);
        }

        if ($withSpeakers = $context['withSpeakers'] ?? true) {
            if (null !== $speakers = $talk->getSpeakers()) {
                $speakers = $this->speakerRepository->getTalkSpeakers($talk);
                $data['speakers'] = $this->normalizer->normalize($speakers, null, [
                    'withTalks' => false,
                ]);
            }
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Talk;
    }
}
