<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Speaker;
use App\Repository\TalkRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SpeakerNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected TalkRepository $talkRepository,
    ) {
    }

    /**
     * @param Speaker $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        if (null === $avatarUrl = $data->getAvatarUrl()) {
            if (null !== $githubUsername = $data->getGithubUsername()) {
                $avatarUrl = 'https://github.com/'.$githubUsername.'.png';
            } elseif (null !== $xUsername = $data->getXUsername()) {
                $avatarUrl = 'https://unavatar.io/twitter/'.$xUsername;
            }
        }

        $speakerData = [
            'id' => $data->getId(),
            'firstName' => $data->getFirstName(),
            'lastName' => $data->getLastName(),
            'slug' => $data->getSlug(),
            'description' => $data->getDescription(),
            'xUsername' => $data->getXUsername(),
            'githubUsername' => $data->getGithubUsername(),
            'speakerDeckUsername' => $data->getSpeakerDeckUsername(),
            'mastodonUsername' => $data->getMastodonUsername(),
            'blueskyUsername' => $data->getBlueskyUsername(),
            'website' => $data->getWebsite(),
            'avatarUrl' => $avatarUrl,
        ];

        if (true === $withCountTalks = $context['withCountTalks'] ?? false) {
            $speakerData['countTalks'] = $this->talkRepository->countSpeakerTalks($data);
        }

        if (true === $withTalks = $context['withTalks'] ?? false) {
            $talks = $this->talkRepository->getSpeakerTalks($data);
            $speakerData['talks'] = $this->normalizer->normalize($talks);
        }

        return $speakerData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Speaker;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Speaker::class => true,
        ];
    }
}
