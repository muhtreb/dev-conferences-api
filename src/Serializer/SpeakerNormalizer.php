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
        if (null !== $githubUsername = $speaker->getGithubUsername()) {
            $avatarUrl = 'https://github.com/' . $githubUsername . '.png';
        } else if (null !== $xUsername = $speaker->getXUsername()) {
            $avatarUrl = 'https://unavatar.io/twitter/' . $xUsername;
        }

        $data = [
            'id' => $speaker->getId(),
            'firstName' => $speaker->getFirstName(),
            'lastName' => $speaker->getLastName(),
            'slug' => $speaker->getSlug(),
            'description' => $speaker->getDescription(),
            'xUsername' => $speaker->getXUsername(),
            'githubUsername' => $speaker->getGithubUsername(),
            'speakerDeckUsername' => $speaker->getSpeakerDeckUsername(),
            'mastodonUsername' => $speaker->getMastodonUsername(),
            'blueskyUsername' => $speaker->getBlueskyUsername(),
            'website' => $speaker->getWebsite(),
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
