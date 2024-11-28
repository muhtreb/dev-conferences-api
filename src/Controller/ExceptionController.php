<?php

namespace  App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/exception')]
class ExceptionController extends AbstractController
{
    public function __invoke()
    {
        throw new \Exception('This is an exception');
    }
}