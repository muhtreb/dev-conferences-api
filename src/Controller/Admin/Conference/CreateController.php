<?php

namespace App\Controller\Admin\Conference;

use App\DomainObject\ConferenceDomainObject;
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
class CreateController extends AbstractController
{
    #[Route('/admin/conferences', name: 'api_admin_conference_create', methods: ['POST'])]
    #[OA\Tag(name: 'Conference')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        ConferenceManager $conferenceManager,
        #[MapRequestPayload(
            validationGroups: ['create']
        )] ConferenceDomainObject $dto,
    ): JsonResponse {
        $conference = $conferenceManager->createConferenceFromDTO($dto);

        return new JsonResponse($normalizer->normalize($conference));
    }
}
