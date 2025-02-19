<?php

namespace App\Controller\Talk;

use App\Repository\TalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FetchIdsController extends AbstractController
{
    #[Route(
        path: '/talks/fetch/ids',
        name: 'api_talk_fetch_ids',
        methods: ['POST']
    )]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $ids = explode(',', trim(preg_replace('/\s+/', '', $request->getContent())));

        if (0 === count($ids)) {
            return new JsonResponse([]);
        }

        $talks = $talkRepository->findBy([
            'id' => $ids,
        ]);

        return new JsonResponse($normalizer->normalize($talks, null, ['withSpeakers' => false]));
    }
}
