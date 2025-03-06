<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Manager\Admin\ConferenceEditionManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class RefreshTalksController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/editions/{conferenceEdition}/refresh/talks',
        name: 'api_conference_edition_refresh_talks',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        NormalizerInterface $normalizer,
        ConferenceEditionManager $conferenceEditionManager,
    ): JsonResponse {
        $conferenceEditionManager->refreshTalks($conferenceEdition);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
