<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MeController extends AbstractController
{
    #[Route('/me', name: 'api_security_me', methods: ['GET'])]
    public function __invoke(NormalizerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->normalize($this->getUser()));
    }
}
