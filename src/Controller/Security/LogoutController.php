<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'api_security_logout', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
