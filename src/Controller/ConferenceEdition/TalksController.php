<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Repository\TalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TalksController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}/talks',
        name: 'api_conference_edition_talks',
        methods: ['GET']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse
    {
        $talks = $talkRepository->findBy(['conferenceEdition' => $conferenceEdition], ['position' => 'ASC']);
        return new JsonResponse($normalizer->normalize($talks));
    }
}
