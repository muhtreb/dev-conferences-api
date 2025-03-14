<?php

namespace App\Controller\Talk;

use App\DomainObject\MetaDomainObject;
use App\Repository\TalkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/talks',
        name: 'api_talk_list',
        methods: ['GET']
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query'
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query'
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Talk list',
    )]
    #[OA\Tag(name: 'Talk')]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * $limit;

        $filters = [];

        $talks = new ArrayCollection($talkRepository->getTalks($filters, ['name' => 'DESC'], $limit, $offset));

        return new JsonResponse([
            'data' => $normalizer->normalize($talks),
            'meta' => new MetaDomainObject(
                page: $page,
                count: $talkRepository->countTalks($filters),
                limit: $limit
            ),
        ]);
    }
}
