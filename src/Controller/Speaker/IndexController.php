<?php

namespace App\Controller\Speaker;

use App\Repository\SpeakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/speakers',
        name: 'api_speaker_list',
        methods: ['GET']
    )]
    public function __invoke(
        SpeakerRepository $speakerRepository,
        NormalizerInterface $serializer,
        Request $request,
    ): JsonResponse
    {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset');

        $speakers = new ArrayCollection($speakerRepository->findBy([], [], $limit, $offset));

        return new JsonResponse($serializer->normalize($speakers, null, [
            'withTalks' => $request->query->getBoolean('withTalks'),
            'withCountTalks' => $request->query->getBoolean('withCountTalks'),
        ]));
    }
}
