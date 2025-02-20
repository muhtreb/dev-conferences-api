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
        $data = [
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

        if ($withEditions = $context['withEditions'] ?? true) {
            $conferenceEditions = new ArrayCollection($this->conferenceEditionRepository->findBy(['conference' => $data], ['startDate' => 'DESC']));
            $data['editions'] = $this->normalizer->normalize($conferenceEditions, null, [
                'withTalks' => false,
                'withPlaylists' => true,
            ]);
        }

        return $data;
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
