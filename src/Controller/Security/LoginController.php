<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'api_security_login', methods: ['POST'])]
    public function __invoke(NormalizerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->normalize($this->getUser()));
    }
}
