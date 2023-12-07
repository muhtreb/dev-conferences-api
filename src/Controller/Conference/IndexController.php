<?php

namespace App\Controller\Conference;

use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/conferences',
        name: 'api_conference_list',
        methods: ['GET']
    )]
    public function __invoke(
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $serializer,
        Request $request,
    ): JsonResponse
    {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset');
        $withEditions = $request->query->getBoolean('withEditions', true);

        $conferences = new ArrayCollection($conferenceRepository->findBy([], ['name' => 'ASC'], $limit, $offset));
        return new JsonResponse($serializer->normalize($conferences, null, [
            'withEditions' => $withEditions,
        ]));
    }
}
