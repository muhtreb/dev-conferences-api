<?php

namespace App\Controller\ConferenceEdition;

use App\Repository\ConferenceEditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LatestController extends AbstractController
{
    #[Route('/conferences/editions/latest', name: 'api_conference_edition_latest')]
    public function __invoke(
        Request $request,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        return new JsonResponse($serializer->normalize($conferenceEditionRepository->getLastEditions($request->query->get('limit', 12)), null, [
            'withTalks' => false,
        ]));
    }
}
