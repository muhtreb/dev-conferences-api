<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/env')]
class EnvController extends AbstractController
{
    public function __invoke(): Response
    {
        $env = $_ENV;

        return $this->json($env);
    }
}
