<?php

namespace App\Controller\ConferenceEdition;

use App\Repository\ConferenceEditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/fetch/ids',
        name: 'api_conference_edition_fetch_ids',
        methods: ['POST']
    )]
    public function __invoke(
        Request $request,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        $ids = explode(',', trim(preg_replace('/\s+/', '', $request->getContent())));

        if (count($ids) === 0) {
            return new JsonResponse([]);
        }

        $conferenceEditions = $conferenceEditionRepository->findBy([
            'id' => $ids
        ]);
        return new JsonResponse($serializer->normalize($conferenceEditions, null, ['withTalks' => false]));
    }
}
