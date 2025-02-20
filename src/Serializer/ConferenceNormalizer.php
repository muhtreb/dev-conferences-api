<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Conference;
use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConferenceNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function __construct(
        protected ConferenceEditionRepository $conferenceEditionRepository,
    ) {
    }

    /**
     * @param Conference $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $conferenceData = [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'slug' => $data->getSlug(),
            'website' => $data->getWebsite(),
            'twitter' => $data->getTwitter(),
            'description' => $data->getDescription(),
            'headerImageUrl' => $data->getHeaderImageUrl(),
            'thumbnailImageUrl' => $data->getThumbnailImageUrl(),
            'tags' => $this->normalizer->normalize($data->getTags()),
        ];

        if (true === $withEditions = $context['withEditions'] ?? true) {
            $conferenceEditions = new ArrayCollection($this->conferenceEditionRepository->getConferenceEditions(['conference' => $data], ['startDate' => 'DESC'], false));
            $conferenceData['editions'] = $this->normalizer->normalize($conferenceEditions, null, [
                'withTalks' => $context['withTalks'] ?? false,
                'withPlaylists' => $context['withPlaylists'] ?? true,
            ]);
        }

        return $conferenceData;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Conference;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Conference::class => true,
        ];
    }
}
