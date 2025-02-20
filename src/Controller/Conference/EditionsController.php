<?php

namespace App\Controller\Conference;

use App\DomainObject\MetaDomainObject;
use App\Entity\Conference;
use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EditionsController extends AbstractController
{
    #[Route(
        path: '/conferences/{conference}/editions',
        name: 'api_conference_editions_list',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference')]
    public function __invoke(
        Conference $conference,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $normalizer,
        Request $request,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $offset = $limit * ($page - 1);

        $filters = ['conference' => $conference];
        $conferenceEditions = new ArrayCollection($conferenceEditionRepository->findBy($filters, ['name' => 'ASC'], $limit, $offset));

        return new JsonResponse([
            'data' => $normalizer->normalize($conferenceEditions, null, [
                'withTalks' => false,
                'withPlaylistImports' => false,
            ]),
            'meta' => MetaDomainObject::create($page, $conferenceEditionRepository->count($filters)),
        ]);
    }
}
