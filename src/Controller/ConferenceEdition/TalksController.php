<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Repository\TalkRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalksController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}/talks',
        name: 'api_conference_edition_talks',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $talks = $talkRepository->findBy(['conferenceEdition' => $conferenceEdition], ['position' => 'ASC']);

        return new JsonResponse($normalizer->normalize($talks));
    }
}
