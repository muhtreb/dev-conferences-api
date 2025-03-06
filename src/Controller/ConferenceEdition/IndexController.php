<?php

namespace App\Controller\ConferenceEdition;

use App\DomainObject\MetaDomainObject;
use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/conferences/editions',
        name: 'api_conference_edition_index',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $normalizer,
        Request $request,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $offset = $limit * ($page - 1);

        $filters = [];

        $conferenceEditions = new ArrayCollection($conferenceEditionRepository->findBy($filters, ['name' => 'ASC'], $limit, $offset));

        return new JsonResponse([
            'data' => $normalizer->normalize($conferenceEditions, null, [
                'withConference' => $request->query->getBoolean('withConference'),
                'withCountTalks' => $request->query->getBoolean('withCountTalks'),
                'withTalks' => $request->query->getBoolean('withTalks'),
                'withPlaylistImports' => $request->query->getBoolean('withPlaylistImports'),
            ]),
            'meta' => MetaDomainObject::create($page, $conferenceEditionRepository->count($filters)),
        ]);
    }
}
