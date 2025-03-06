<?php

namespace App\Controller\Conference;

use App\Repository\ConferenceRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/conferences/fetch/ids',
        name: 'api_conference_fetch_ids',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Conference')]
    #[OA\RequestBody(
        request: 'Conference IDs',
        description: 'A list of conference IDs separated by commas',
        required: true,
    )]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $ids = explode(',', trim((string) preg_replace('/\s+/', '', $request->getContent())));

        if (0 === count($ids)) {
            return new JsonResponse([]);
        }

        $conferences = $conferenceRepository->findBy([
            'id' => $ids,
        ]);

        return new JsonResponse($normalizer->normalize($conferences, null, ['withEditions' => false]));
    }
}
