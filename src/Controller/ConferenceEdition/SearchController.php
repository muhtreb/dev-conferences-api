<?php

namespace App\Controller\ConferenceEdition;

use App\Controller\SearchTrait;
use App\Entity\ConferenceEdition;
use App\Repository\ConferenceEditionRepository;
use App\Service\SearchClient;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    use SearchTrait;

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
        SearchClient $searchClient,
    ): JsonResponse {
        $data = $searchClient->search('conference_editions', $request->query->get('query', ''), [
            'attributesToRetrieve' => [
                'objectID',
            ],
            'hitsPerPage' => $request->query->getInt('limit', 30),
            'page' => $request->query->getInt('page', 1),
            'sort' => [
                'date:desc',
            ],
        ]);

        $conferenceEditionIds = [];
        foreach ($data['hits'] as $hit) {
            $conferenceEditionIds[] = $hit['objectID'];
        }

        $conferenceEditions = $conferenceEditionRepository->findBy(['id' => $conferenceEditionIds]);

        usort($conferenceEditions, fn(ConferenceEdition $a, ConferenceEdition $b) => array_search($a->getId(), $conferenceEditionIds) - array_search($b->getId(), $conferenceEditionIds));

        return new JsonResponse([
            'data' => $normalizer->normalize($conferenceEditions, null, [
                'withTalks' => false,
                'withPlaylistImports' => false,
            ]),
            'meta' => $this->getMeta($data),
        ]);
    }
}
