<?php

namespace App\Controller\Tag;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShowController extends AbstractController
{
    #[Route('/tags/{tag}', name: 'api_tag_show')]
    public function __invoke(
        Tag $tag,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        return new JsonResponse($serializer->normalize($tag));
    }
}
