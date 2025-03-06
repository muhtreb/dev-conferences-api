<?php

namespace App\Controller\Admin\Speaker;

use App\Entity\Speaker;
use App\Manager\Admin\SpeakerManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/admin/speakers/{speaker}',
        name: 'api_admin_speaker_remove',
        requirements: ['speaker' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['DELETE']
    )]
    #[OA\Tag(name: 'Speaker')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        Speaker $speaker,
        SpeakerManager $speakerManager,
    ): JsonResponse {
        $speakerManager->removeSpeaker($speaker);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
