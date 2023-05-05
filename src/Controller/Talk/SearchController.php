<?php

namespace App\Controller\Talk;

use App\Controller\SearchTrait;
use App\Entity\Talk;
use App\Repository\TalkRepository;
use App\Service\SearchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    use SearchTrait;

    #[Route('/talks/search', name: 'api_talk_search', methods: ['GET'])]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $serializer,
        SearchClient $searchClient
    ): JsonResponse
    {
        $data = $searchClient->search('talks', $request->query->get('query', ''), [
            'attributesToRetrieve' => [
                'objectID',
            ],
            'hitsPerPage' => $request->query->getInt('limit', 30),
            'page' => $request->query->getInt('page', 1)
        ]);

        $talkIds = [];
        foreach ($data['hits'] as $hit) {
            $talkIds[] = $hit['objectID'];
        }

        $talks = $talkRepository->findBy(['id' => $talkIds]);

        usort($talks, function (Talk $a, Talk $b) use ($talkIds) {
            return array_search($a->getId(), $talkIds) - array_search($b->getId(), $talkIds);
        });

        return new JsonResponse([
            'data' => $serializer->normalize($talks),
            'meta' => $this->getMeta($data),
        ]);
    }
}
