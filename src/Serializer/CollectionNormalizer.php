<?php

namespace App\Serializer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CollectionNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Collection;
    }

    /**
     * @param Collection $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return $object->map(fn($item) => $this->normalizer->normalize($item, $format, $context))->getValues();
    }
}