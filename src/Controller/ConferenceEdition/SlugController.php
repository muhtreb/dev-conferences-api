<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        NormalizerInterface $normalizer,
        ValidatorInterface $validator,
    ): JsonResponse {
        $validation = $validator->validate($conferenceEdition);
        if (count($validation) > 0) {
            return new JsonResponse($normalizer->normalize($validation), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($normalizer->normalize($conferenceEdition, null, [
            'withConference' => $request->query->getBoolean('withConference', true),
            'withTalks' => $request->query->getBoolean('withTalks', true),
            'withPlaylists' => $request->query->getBoolean('withPlaylists', true),
        ]));
    }
}
