<?php

namespace App\Controller\ConferenceEdition;

use App\Repository\ConferenceEditionRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/fetch/ids',
        name: 'api_conference_edition_fetch_ids',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        Request $request,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $ids = explode(',', trim((string) preg_replace('/\s+/', '', $request->getContent())));

        if (0 === count($ids)) {
            return new JsonResponse([]);
        }

        $conferenceEditions = $conferenceEditionRepository->findBy([
            'id' => $ids,
        ]);

        return new JsonResponse($normalizer->normalize($conferenceEditions, null, ['withTalks' => false]));
    }
}
