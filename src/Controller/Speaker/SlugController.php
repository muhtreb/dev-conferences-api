<?php

namespace App\Controller\Speaker;

use App\Entity\Speaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/speakers/slug/{slug}',
        name: 'api_speaker_slug',
        requirements: ['slug' => '.*']
    )]
    public function __invoke(
        Speaker $speaker,
        NormalizerInterface $serializer,
    ): JsonResponse {
        return new JsonResponse($serializer->normalize($speaker));
    }
}
