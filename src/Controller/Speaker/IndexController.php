<?php

namespace App\Controller\Speaker;

use App\DomainObject\MetaDomainObject;
use App\Repository\SpeakerRepository;
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
        path: '/speakers',
        name: 'api_speaker_list',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Speaker')]
    public function __invoke(
        SpeakerRepository $speakerRepository,
        NormalizerInterface $normalizer,
        Request $request,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $offset = $limit * ($page - 1);

        $filters = [];

        $speakers = new ArrayCollection($speakerRepository->findBy($filters, [], $limit, $offset));

        return new JsonResponse([
            'data' => $normalizer->normalize($speakers, null, [
                'withTalks' => $request->query->getBoolean('withTalks'),
                'withCountTalks' => $request->query->getBoolean('withCountTalks'),
            ]),
            'meta' => MetaDomainObject::create($page, $speakerRepository->count($filters)),
        ]);
    }
}
