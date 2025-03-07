<?php

namespace App\Controller\Speaker;

use App\DomainObject\MetaDomainObject;
use App\DomainObject\Search\SearchQueryDomainObject;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
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
        path: '/speakers/search',
        name: 'api_speaker_search',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Speaker')]
    public function __invoke(
        Request $request,
        SpeakerRepository $speakerRepository,
        NormalizerInterface $normalizer,
        SearchClientInterface $searchClient,
        TagAwareCacheInterface $cache
    ): JsonResponse
    {
        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);
        $query = $request->query->get('query', '');

        $cacheKey = 'search-talks-' . md5(sprintf('query=%s-limit=%d-page=%d', $query, $limit, $page));
        $data = $cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($searchClient, $query, $limit, $page, $speakerRepository, $normalizer): array {
                $item->tag(['speakers']);

                $searchResults = $searchClient->search('speakers', new SearchQueryDomainObject(
                    query: $query,
                    limit: $limit,
                    page: $page,
                    sortField: 'countTalks',
                    sortDirection: 'desc'
                ));

                $speakerIds = [];
                foreach ($searchResults->items as $hit) {
                    $speakerIds[] = $hit->id;
                }

                $speakers = $speakerRepository->findBy(['id' => $speakerIds]);

                usort($speakers, fn(Speaker $a, Speaker $b) => array_search($a->getId(), $speakerIds) - array_search($b->getId(), $speakerIds));

                return [
                    'data' => $normalizer->normalize($speakers, null, ['withTalks' => false]),
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
