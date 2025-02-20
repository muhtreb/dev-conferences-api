<?php

namespace App\Controller\Tag;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/tags',
        name: 'api_tag_list',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'Tag')]
    public function __invoke(
        TagRepository $tagRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $tags = new ArrayCollection($tagRepository->findBy([], ['name' => 'ASC']));

        return new JsonResponse($normalizer->normalize($tags));
    }
}
