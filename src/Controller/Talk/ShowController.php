<?php

namespace App\Controller\Talk;

use App\Entity\Talk;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/talks/{talk}',
        name: 'api_talks_show',
        requirements: ['talk' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        Talk $talk,
        NormalizerInterface $serializer
    ): JsonResponse
    {
        return new JsonResponse($serializer->normalize($talk, null, [
            'withPrevNextTalks' => true,
        ]));
    }
}
