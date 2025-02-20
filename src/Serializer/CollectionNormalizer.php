<?php

namespace App\Serializer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CollectionNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Collection $data
     */
    public function normalize($data, ?string $format = null, array $context = []): array
    {
        return $data->map(fn ($item) => $this->normalizer->normalize($item, $format, $context))->getValues();
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Collection;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Collection::class => true,
        ];
    }
}
