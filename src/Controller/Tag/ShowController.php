<?php

namespace App\Controller\Tag;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route(
        path: '/tags/{tag}',
        name: 'api_tag_show',
        methods: ['GET']
    )]
    public function __invoke(
        Tag $tag,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse($normalizer->normalize($tag));
    }
}
