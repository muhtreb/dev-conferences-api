<?php

namespace App\Controller\Conference;

use App\DomainObject\MetaDomainObject;
use App\DomainObject\Search\SearchQueryDomainObject;
use App\Entity\Conference;
use App\Repository\ConferenceRepository;
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
        path: '/conferences/search',
        name: 'api_conference_search',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference')]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $normalizer,
        SearchClientInterface $searchClient,
        TagAwareCacheInterface $cache,
    ): JsonResponse {
        $withEditions = $request->query->getBoolean('withEditions', true);

        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query', '');

        $cacheKey = 'search-conference-'.md5(sprintf('query=%s-limit=%d-page=%d-withEditions=%s', $query, $limit, $page, $withEditions ? 'true' : 'false'));
        $data = $cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($searchClient, $query, $limit, $page, $conferenceRepository, $normalizer, $withEditions): array {
                $item->tag(['conferences']);

                $searchResults = $searchClient->search('conferences', new SearchQueryDomainObject(
                    query: $query,
                    fields: ['name^2', 'description'],
                    limit: $limit,
                    page: $page,
                    sortField: 'name',
                    sortDirection: 'asc'
                ));

                $conferenceIds = [];
                foreach ($searchResults->items as $hit) {
                    $conferenceIds[] = $hit->id;
                }

                $conferences = $conferenceRepository->getConferencesByIds($conferenceIds);

                usort($conferences, fn (Conference $a, Conference $b) => array_search($a->getId(), $conferenceIds) - array_search($b->getId(), $conferenceIds));

                return [
                    'data' => $normalizer->normalize($conferences, null, [
                        'withEditions' => $withEditions,
                    ]),
                    'meta' => new MetaDomainObject(
                        page: $page,
                        count: $searchResults->meta->total,
                        limit: $limit,
                    ),
                ];
            }
        );

        return new JsonResponse($data);
    }
}
