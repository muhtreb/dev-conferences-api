<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/server')]
class ServerController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->json($_SERVER);
    }
}
