<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\ConferenceEditionDomainObject;
use App\Entity\ConferenceEdition;
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
class EditController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/editions/{conferenceEdition}',
        name: 'api_admin_conference_edition_edit',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PUT']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        NormalizerInterface $normalizer,
        ConferenceEditionManager $conferenceEditionManager,
        #[MapRequestPayload(
            validationGroups: ['edit']
        )] ConferenceEditionDomainObject $dto,
    ): JsonResponse {
        $conferenceEdition = $conferenceEditionManager->updateConferenceEditionFromDTO($conferenceEdition, $dto);

        return new JsonResponse($normalizer->normalize($conferenceEdition));
    }
}
