<?php

namespace App\Controller\Talk;

use App\Entity\Talk;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/talks/{talk}',
        name: 'api_talks_show',
        requirements: ['talk' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
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
