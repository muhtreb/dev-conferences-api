<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Talk;
use App\Repository\SpeakerRepository;
use App\Repository\TalkRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalkNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        private SpeakerRepository $speakerRepository,
        private TalkRepository $talkRepository,
    ) {
    }

    /**
     * @param Talk $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $talkData = [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'description' => $data->getDescription(),
            'date' => $data->getDate()->format('Y-m-d'),
            'youtubeId' => $data->getYoutubeId(),
            'duration' => $data->getDuration(),
            'images' => [
                'thumbnail' => $data->getThumbnailImageUrl(),
                'poster' => $data->getPosterImageUrl(),
            ],
            'slug' => $data->getSlug(),
        ];

        if (true === $withPrevNextTalks = $context['withPrevNextTalks'] ?? false) {
            $prevTalk = $this->talkRepository->findOneBy([
                'conferenceEdition' => $data->getConferenceEdition(),
                'position' => $data->getPosition() - 1,
            ]);

            $talkData['prevTalk'] = null;
            if (null !== $prevTalk) {
                $talkData['prevTalk'] = $this->normalizer->normalize($prevTalk, null, [
                    'withPrevNextTalks' => false,
                    'withEdition' => false,
                    'withSpeakers' => true,
                ]);
            }

            $nextTalk = $this->talkRepository->findOneBy([
                'conferenceEdition' => $data->getConferenceEdition(),
                'position' => $data->getPosition() + 1,
            ]);

            $talkData['nextTalk'] = null;
            if (null !== $nextTalk) {
                $talkData['nextTalk'] = $this->normalizer->normalize($nextTalk, null, [
                    'withPrevNextTalks' => false,
                    'withEdition' => false,
                    'withSpeakers' => true,
                ]);
            }
        }

        if (true === $withEdition = $context['withEdition'] ?? true) {
            $talkData['edition'] = $this->normalizer->normalize($data->getConferenceEdition(), null, [
                'withConference' => true,
                'withTalks' => false,
            ]);
        }

        if (true === $withSpeakers = $context['withSpeakers'] ?? true) {
            if (null !== $data->getSpeakers()) {
                $speakers = $this->speakerRepository->getTalkSpeakers($data);
                $talkData['speakers'] = $this->normalizer->normalize($speakers, null, [
                    'withTalks' => false,
                ]);
            }
        }

        return $talkData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Talk;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Talk::class => true,
        ];
    }
}
