<?php

namespace App\Controller\Conference;

use App\Entity\Conference;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SlugController extends AbstractController
{
    #[Route(
        path: '/conferences/slug/{slug:conference}',
        name: 'api_conference_slug',
        requirements: ['slug' => '[a-z0-9-]+'],
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Conference')]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($conference));
    }
}
