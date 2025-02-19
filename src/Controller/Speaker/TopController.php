<?php

namespace App\Controller\Speaker;

use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use App\Service\SearchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TopController extends AbstractController
{
    #[Route(
        path: '/speakers/top',
        name: 'api_speakers_top',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        SpeakerRepository $speakerRepository,
        NormalizerInterface $normalizer,
        SearchClient $searchClient
    ): JsonResponse {
        $data = $searchClient->search('speakers', '', [
            'sort' => ['countTalks:desc'],
            'limit' => $request->query->getInt('limit', 10),
            'attributesToRetrieve' => ['objectID'],
        ]);

        $speakerIds = [];
        foreach ($data['hits'] as $hit) {
            $speakerIds[] = $hit['objectID'];
        }

        $speakers = $speakerRepository->findBy(['id' => $speakerIds]);

        usort($speakers, function (Speaker $a, Speaker $b) use ($speakerIds) {
            return array_search($a->getId(), $speakerIds) - array_search($b->getId(), $speakerIds);
        });

        return new JsonResponse(
            $normalizer->normalize($speakers, null, ['withTalks' => false])
        );
    }
}
