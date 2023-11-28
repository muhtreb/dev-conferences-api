<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}',
        name: 'api_conference_edition_show',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['GET']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        NormalizerInterface $serializer
    ): JsonResponse
    {
        return new JsonResponse($serializer->normalize($conferenceEdition, null, [
            'withConference' => $request->query->getBoolean('withConference', true),
            'withTalks' => $request->query->getBoolean('withTalks', true),
            'withPlaylists' => $request->query->getBoolean('withPlaylists', true),
        ]));
    }
}
