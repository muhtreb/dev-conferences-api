<?php

namespace App\Controller\Speaker;

use App\Repository\SpeakerRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/speakers/fetch/ids',
        name: 'api_speaker_fetch_ids',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Speaker')]
    public function __invoke(
        Request $request,
        SpeakerRepository $speakerRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $ids = explode(',', trim((string) preg_replace('/\s+/', '', $request->getContent())));

        if (0 === count($ids)) {
            return new JsonResponse([]);
        }

        $speakers = $speakerRepository->findBy([
            'id' => $ids,
        ]);

        return new JsonResponse($normalizer->normalize($speakers, null, ['withTalks' => false]));
    }
}
