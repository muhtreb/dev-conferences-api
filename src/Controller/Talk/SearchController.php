<?php

namespace App\Controller\Talk;

use App\DomainObject\MetaDomainObject;
use App\DomainObject\Search\SearchQueryDomainObject;
use App\Entity\Talk;
use App\Repository\TalkRepository;
use App\Service\Search\Client\SearchClientInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    #[Route(
        path: '/talks/search',
        name: 'api_talk_search',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Talk')]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
        SearchClientInterface $searchClient,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);

        $searchResults = $searchClient->search('talks', new SearchQueryDomainObject(
            query: $request->query->get('query', ''),
            fields: ['name^2', 'description', 'speaker.firstName', 'speaker.lastName'],
            limit: $limit,
            page: $page,
            sortField: 'date',
            sortDirection: 'desc'
        ));

        $talkIds = [];
        foreach ($searchResults->items as $hit) {
            $talkIds[] = $hit->id;
        }

        $talks = $talkRepository->findBy(['id' => $talkIds]);

        usort($talks, fn (Talk $a, Talk $b) => array_search($a->getId(), $talkIds) - array_search($b->getId(), $talkIds));

        return new JsonResponse([
            'data' => $normalizer->normalize($talks),
            'meta' => new MetaDomainObject(
                page: $page,
                count: $searchResults->meta->total,
                limit: $limit,
            ),
        ]);
    }
}
