<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Speaker;
use App\Repository\TalkRepository;
use Cocur\Slugify\Slugify;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SpeakerNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected TalkRepository $talkRepository
    ) {
    }

    /**
     * @param Speaker $speaker
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($speaker, string $format = null, array $context = []): array
    {
        $avatarUrl = null;
        if (null !== $github = $speaker->getGithub()) {
            $avatarUrl = 'https://github.com/' . $github . '.png';
        } else if (null !== $twitter = $speaker->getTwitter()) {
            $avatarUrl = 'https://unavatar.io/twitter/' . $twitter;
        }

        $data = [
            'id' => $speaker->getId(),
            'firstName' => $speaker->getFirstName(),
            'lastName' => $speaker->getLastName(),
            'slug' => $speaker->getSlug(),
            'description' => $speaker->getDescription(),
            'twitter' => $speaker->getTwitter(),
            'github' => $speaker->getGithub(),
            'avatarUrl' => $avatarUrl,
        ];

        if ($withCountTalks = $context['withCountTalks'] ?? false) {
            $data['countTalks'] = $this->talkRepository->countSpeakerTalks($speaker);
        }

        if ($withTalks = $context['withTalks'] ?? false) {
            $talks = $this->talkRepository->getSpeakerTalks($speaker);
            $data['talks'] = $this->normalizer->normalize($talks);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Speaker;
    }
}
