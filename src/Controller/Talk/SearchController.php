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
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

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
        TagAwareCacheInterface $cache,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query', '');

        $cacheKey = 'search-talks-'.md5(sprintf('query=%s-limit=%d-page=%d', $query, $limit, $page));
        $data = $cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($searchClient, $query, $limit, $page, $talkRepository, $normalizer): array {
                $item->tag(['search-talks']);

                $searchResults = $searchClient->search('talks', new SearchQueryDomainObject(
                    query: $query,
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

                return [
                    'data' => $normalizer->normalize($talks),
                    'meta' => new MetaDomainObject(
                        page: $page,
                        count: $searchResults->meta->total,
                        limit: $limit,
                    ),
                ];
            });

        return new JsonResponse($data);
    }
}
