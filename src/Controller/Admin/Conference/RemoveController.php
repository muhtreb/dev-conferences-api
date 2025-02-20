<?php

namespace App\Controller\Admin\Conference;

use App\Entity\Conference;
use App\Manager\Admin\ConferenceManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/{conference}',
        name: 'api_admin_conference_remove',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'Conference')]
    public function __invoke(
        Conference $conference,
        ConferenceManager $conferenceManager,
    ): JsonResponse {
        $conferenceManager->removeConference($conference);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
