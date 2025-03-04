<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\ConferenceEditionDomainObject;
use App\Entity\Conference;
use App\Manager\Admin\ConferenceEditionManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    #[Route('/admin/conferences/{conference}/editions', name: 'api_admin_conference_edition_create', methods: ['POST'])]
    #[OA\Tag(name: 'Conference Edition')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $normalizer,
        ConferenceEditionManager $conferenceEditionManager,
        #[MapRequestPayload(
            validationGroups: ['create']
        )] ConferenceEditionDomainObject $dto,
    ): JsonResponse {
        $dto->conference = $conference;
        $conferenceEdition = $conferenceEditionManager->createConferenceEditionFromDTO($dto);

        return new JsonResponse($normalizer->normalize($conferenceEdition));
    }
}
