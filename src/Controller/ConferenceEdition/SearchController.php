<?php

namespace App\Controller\ConferenceEdition;

use App\DomainObject\MetaDomainObject;
use App\DomainObject\Search\SearchQueryDomainObject;
use App\Entity\ConferenceEdition;
use App\Repository\ConferenceEditionRepository;
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
        path: '/conferences/editions/search',
        name: 'api_conference_edition_search',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        Request $request,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $normalizer,
        SearchClientInterface $searchClient,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query', '');

        $cacheKey = 'search-conference-editions-' . md5(sprintf('query=%s-limit=%d-page=%d', $query, $limit, $page));
        $data = $cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($searchClient, $query, $limit, $page, $conferenceEditionRepository, $normalizer): array {
                $item->tag(['conference-editions']);

                $searchResults = $searchClient->search('conference_editions', new SearchQueryDomainObject(
                    query: $query,
                    fields: ['name', 'description'],
                    limit: $limit,
                    page: $page,
                    sortField: 'date',
                    sortDirection: 'desc'
                ));

                $conferenceEditionIds = [];
                foreach ($searchResults->items as $hit) {
                    $conferenceEditionIds[] = $hit->id;
                }

                $conferenceEditions = $conferenceEditionRepository->findBy(['id' => $conferenceEditionIds]);

                usort($conferenceEditions, fn(ConferenceEdition $a, ConferenceEdition $b) => array_search($a->getId(), $conferenceEditionIds) - array_search($b->getId(), $conferenceEditionIds));

                return [
                    'data' => $normalizer->normalize($conferenceEditions, null, [
                        'withTalks' => false,
                        'withPlaylistImports' => false,
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
