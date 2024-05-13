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
     * @param Conference $conference
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($conference, ?string $format = null, array $context = []): array
    {
        $data = [
            'id' => $conference->getId(),
            'name' => $conference->getName(),
            'slug' => $conference->getSlug(),
            'website' => $conference->getWebsite(),
            'twitter' => $conference->getTwitter(),
            'description' => $conference->getDescription(),
            'headerImageUrl' => $conference->getHeaderImageUrl(),
            'thumbnailImageUrl' => $conference->getThumbnailImageUrl(),
            'tags' => $this->normalizer->normalize($conference->getTags()),
        ];

        if ($withEditions = $context['withEditions'] ?? true) {
            $conferenceEditions = new ArrayCollection($this->conferenceEditionRepository->findBy(['conference' => $conference], ['startDate' => 'DESC']));
            $data['editions'] = $this->normalizer->normalize($conferenceEditions, null, [
                'withTalks' => false,
                'withPlaylists' => true,
            ]);
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
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
