<?php

namespace App\Controller\Speaker;

use App\Entity\Speaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/speakers/{speaker}',
        name: 'api_speakers_show',
        requirements: ['speaker' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['GET']
    )]
    public function __invoke(
        Speaker $speaker,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($speaker));
    }
}
