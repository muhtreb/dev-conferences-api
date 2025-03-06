<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}',
        name: 'api_conference_edition_show',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($conferenceEdition, null, [
            'withConference' => $request->query->getBoolean('withConference', true),
            'withTalks' => $request->query->getBoolean('withTalks', true),
            'withPlaylists' => $request->query->getBoolean('withPlaylists', true),
        ]));
    }
}
