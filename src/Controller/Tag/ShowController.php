<?php

namespace App\Controller\Tag;

use App\Entity\Tag;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/tags/{tag}',
        name: 'api_tag_show',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Tag')]
    public function __invoke(
        Tag $tag,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($tag));
    }
}
