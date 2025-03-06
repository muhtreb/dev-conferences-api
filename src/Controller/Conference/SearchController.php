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
    ): JsonResponse {
        $withEditions = $request->query->getBoolean('withEditions', true);

        $limit = $request->query->getInt('limit', 24);
        $page = $request->query->getInt('page', 1);

        $searchResults = $searchClient->search('conferences', new SearchQueryDomainObject(
            query: $request->query->get('query', ''),
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

        return new JsonResponse([
            'data' => $normalizer->normalize($conferences, null, [
                'withEditions' => $withEditions,
            ]),
            'meta' => new MetaDomainObject(
                page: $page,
                count: $searchResults->meta->total,
                limit: $limit,
            ),
        ]);
    }
}
