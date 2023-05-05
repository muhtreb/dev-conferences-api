<?php

namespace App\Controller\Conference;

use App\Entity\Conference;
use App\Manager\Admin\ConferenceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/conferences/{conference}',
        name: 'api_conference_remove',
        methods: ['DELETE'],
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        Conference $conference,
        ConferenceManager $conferenceManager,
    ): JsonResponse
    {
        $conferenceManager->removeConference($conference);
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
