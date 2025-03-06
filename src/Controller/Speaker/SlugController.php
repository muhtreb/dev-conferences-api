<?php

namespace App\Controller\Speaker;

use App\Entity\Speaker;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/speakers/slug/{slug:speaker}',
        name: 'api_speaker_slug',
        requirements: ['slug' => '.*'],
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Speaker')]
    public function __invoke(
        Speaker $speaker,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($speaker, null, [
            'withTalks' => true,
            'withCountTalks' => true,
        ]));
    }
}
