<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/slug/{slug:conferenceEdition}',
        name: 'api_conference_edition_slug',
        requirements: ['slug' => '.*'],
        methods: ['GET']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        NormalizerInterface $normalizer,
        ValidatorInterface $validator
    ): JsonResponse {
        $validation = $validator->validate($conferenceEdition);
        if (count($validation) > 0) {
            return new JsonResponse($normalizer->normalize($validation), 400);
        }
        return new JsonResponse($normalizer->normalize($conferenceEdition, null, [
            'withConference' => $request->query->getBoolean('withConference', true),
            'withTalks' => $request->query->getBoolean('withTalks', true),
            'withPlaylists' => $request->query->getBoolean('withPlaylists', true),
        ]));
    }
}
