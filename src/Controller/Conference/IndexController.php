<?php

namespace App\Controller\Conference;

use App\DomainObject\MetaDomainObject;
use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/conferences',
        name: 'api_conference_index',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference')]
    #[OA\Get(
        description: 'Get a list of conferences',
    )]
    public function __invoke(
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $normalizer,
        Request $request,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $offset = $limit * ($page - 1);
        $withEditions = $request->query->getBoolean('withEditions', true);

        $filters = [];

        $conferences = new ArrayCollection($conferenceRepository->getConferences($filters, ['name' => 'ASC'], $limit, $offset));
        $countConferences = $conferenceRepository->countConferences($filters);

        return new JsonResponse([
            'data' => $normalizer->normalize($conferences, null, [
                'withEditions' => $withEditions,
            ]),
            'meta' => MetaDomainObject::create($page, $countConferences),
        ]);
    }
}
