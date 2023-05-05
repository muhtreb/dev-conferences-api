<?php

namespace App\Controller\Conference;

use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/conferences/{conference}',
        name: 'api_conference_show',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        return new JsonResponse($serializer->normalize($conference));
    }
}
