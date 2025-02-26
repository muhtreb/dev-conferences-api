<?php

namespace App\Controller\Admin\Conference;

use App\DomainObject\ConferenceDomainObject;
use App\Entity\Conference;
use App\Manager\Admin\ConferenceManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class EditController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/{conference}',
        name: 'api_admin_conference_edit',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
    )]
    #[OA\Tag(name: 'Conference')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $normalizer,
        ConferenceManager $conferenceManager,
        #[MapRequestPayload(
            validationGroups: ['edit']
        )] ConferenceDomainObject $dto,
    ): JsonResponse {
        $conference = $conferenceManager->updateConferenceFromDTO($conference, $dto);

        return new JsonResponse($normalizer->normalize($conference));
    }
}
