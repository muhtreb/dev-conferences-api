<?php

namespace App\Controller\Speaker;

use App\Repository\SpeakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route('/speakers', name: 'api_speaker_list', methods: ['GET'])]
    public function __invoke(
        SpeakerRepository $speakerRepository,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        $speakers = new ArrayCollection($speakerRepository->findAll());
        return new JsonResponse($serializer->normalize($speakers));
    }
}
