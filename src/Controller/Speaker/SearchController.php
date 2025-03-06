<?php

namespace App\Controller\Speaker;

use App\Controller\SearchTrait;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use App\Service\SearchClient;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    use SearchTrait;

    #[Route(
        path: '/speakers/search',
        name: 'api_speaker_search',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Speaker')]
    public function __invoke(
        Request $request,
        SpeakerRepository $speakerRepository,
        NormalizerInterface $normalizer,
        SearchClient $searchClient,
    ): JsonResponse {
        $data = $searchClient->search('speakers', $request->query->get('query', ''), [
            'attributesToRetrieve' => [
                'objectID',
            ],
            'hitsPerPage' => $request->query->getInt('limit', 30),
            'page' => $request->query->getInt('page', 1),
            'sort' => [
                'countTalks:desc',
            ],
        ]);

        $speakerIds = [];
        foreach ($data['hits'] as $hit) {
            $speakerIds[] = $hit['objectID'];
        }

        $speakers = $speakerRepository->findBy(['id' => $speakerIds]);

        usort($speakers, fn (Speaker $a, Speaker $b) => array_search($a->getId(), $speakerIds) - array_search($b->getId(), $speakerIds));

        return new JsonResponse([
            'data' => $normalizer->normalize($speakers, null, ['withTalks' => false]),
            'meta' => $this->getMeta($data),
        ]);
    }
}
