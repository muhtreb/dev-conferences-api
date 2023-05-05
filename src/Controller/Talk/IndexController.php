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
    ): JsonResponse
    {
        $talkRepository->count([]);
        $limit = 50;
        $page = $request->query->get('page', 1);
        $offset = ($page - 1) * $limit;
        $talks = new ArrayCollection($talkRepository->findBy([], ['name' => 'ASC'], $limit, $offset));
        return new JsonResponse($serializer->normalize($talks));
    }
}
