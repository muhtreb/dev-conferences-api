<?php

namespace App\Controller\Conference;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/conferences/fetch/ids',
        name: 'api_conference_fetch_ids',
        methods: ['POST']
    )]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse
    {
        $ids = explode(',', trim(preg_replace('/\s+/', '', $request->getContent())));

        if (count($ids) === 0) {
            return new JsonResponse([]);
        }

        $conferences = $conferenceRepository->findBy([
            'id' => $ids
        ]);
        return new JsonResponse($normalizer->normalize($conferences, null, ['withEditions' => false]));
    }
}
