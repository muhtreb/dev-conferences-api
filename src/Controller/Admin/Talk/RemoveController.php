<?php

namespace App\Controller\Admin\Talk;

use App\Entity\Talk;
use App\Manager\Admin\TalkManager;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/admin/talks/{talk}',
        name: 'api_admin_talk_remove',
        requirements: ['talk' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['DELETE']
    )]
    public function __invoke(
        Talk $talk,
        TalkManager $talkManager,
    ): JsonResponse
    {
        $talkManager->removeTalk($talk);
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
