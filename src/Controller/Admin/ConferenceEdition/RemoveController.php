<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Manager\Admin\ConferenceEditionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/editions/{conferenceEdition}',
        name: 'api_admin_conference_remove',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['DELETE']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        ConferenceEditionManager $conferenceEditionManager,
    ): JsonResponse {
        $conferenceEditionManager->removeConferenceEdition($conferenceEdition);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
