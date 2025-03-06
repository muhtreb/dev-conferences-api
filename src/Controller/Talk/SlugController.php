<?php

namespace App\Controller\Talk;

use App\Entity\Talk;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/talks/slug/{slug:talk}',
        name: 'api_talk_slug',
        requirements: ['slug' => '[a-z0-9-]+'],
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Talk')]
    public function __invoke(
        Talk $talk,
        Request $request,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($talk, null, [
            'withEdition' => $request->query->getBoolean('withEdition', true),
            'withPrevNextTalks' => $request->query->getBoolean('withPrevNextTalks', true),
        ]));
    }
}
