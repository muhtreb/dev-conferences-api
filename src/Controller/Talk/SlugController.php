<?php

namespace App\Controller\Talk;

use App\Entity\Talk;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/talks/slug/{slug}',
        name: 'api_talk_slug',
        requirements: ['slug' => '[a-z0-9-]+']
    )]
    public function __invoke(
        Talk $talk,
        NormalizerInterface $serializer,
    ): JsonResponse {
        return new JsonResponse($serializer->normalize($talk, null, [
            'withPrevNextTalks' => true,
        ]));
    }
}
