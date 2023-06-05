<?php

namespace App\Controller\Talk;

use App\Entity\Talk;
use App\Manager\Admin\TalkManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class RemoveController extends AbstractController
{
    #[Route(
        path: '/talks/{talk}',
        name: 'api_talk_remove',
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
