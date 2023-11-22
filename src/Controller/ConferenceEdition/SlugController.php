<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/slug/{slug}',
        name: 'api_conference_edition_slug',
        requirements: ['slug' => '.*']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        NormalizerInterface $serializer,
    ): JsonResponse {
        return new JsonResponse($serializer->normalize($conferenceEdition, null, [
            'withConference' => $request->query->getBoolean('withConference', true),
            'withTalks' => $request->query->getBoolean('withTalks', true),
            'withPlaylists' => $request->query->getBoolean('withPlaylists', true),
        ]));
    }
}
