<?php

namespace App\Controller\Talk;

use App\Repository\TalkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route('/talks', name: 'api_talk_list', methods: ['GET'])]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $serializer,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset');
        $talks = new ArrayCollection($talkRepository->findBy([], ['name' => 'ASC'], $limit, $offset));
        return new JsonResponse($serializer->normalize($talks));
    }
}
