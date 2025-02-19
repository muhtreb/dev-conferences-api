<?php

namespace App\Controller\Conference;

use App\Controller\SearchTrait;
use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use App\Service\SearchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    use SearchTrait;

    #[Route(
        path: '/conferences/search',
        name: 'api_conference_search',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $normalizer,
        SearchClient $searchClient,
    ): JsonResponse {
        $data = $searchClient->search('conferences', $request->query->get('query', ''), [
            'attributesToRetrieve' => [
                'objectID',
            ],
            'hitsPerPage' => $request->query->getInt('limit', 24),
            'page' => $request->query->getInt('page', 1),
            'sort' => [
                'name:asc',
            ],
        ]);

        $conferenceIds = [];
        foreach ($data['hits'] as $hit) {
            $conferenceIds[] = $hit['objectID'];
        }

        $conferences = $conferenceRepository->findBy(['id' => $conferenceIds]);

        usort($conferences, function (Conference $a, Conference $b) use ($conferenceIds) {
            return array_search($a->getId(), $conferenceIds) - array_search($b->getId(), $conferenceIds);
        });

        return new JsonResponse([
            'data' => $normalizer->normalize($conferences),
            'meta' => $this->getMeta($data),
        ]);
    }
}
