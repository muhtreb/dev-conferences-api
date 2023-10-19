<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        NormalizerInterface $serializer,
    ): JsonResponse {
        return new JsonResponse($serializer->normalize($conferenceEdition));
    }
}
