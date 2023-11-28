<?php

namespace App\Controller\Tag;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function __invoke(
        TagRepository $tagRepository,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        $tags = new ArrayCollection($tagRepository->findBy([], ['name' => 'ASC']));
        return new JsonResponse($serializer->normalize($tags));
    }
}
