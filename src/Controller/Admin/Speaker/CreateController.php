<?php

namespace App\Controller\Admin\Speaker;

use App\DomainObject\SpeakerDomainObject;
use App\Manager\Admin\SpeakerManager;
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
    #[Route('/admin/speakers', name: 'api_admin_speaker_create', methods: ['POST'])]
    #[OA\Tag(name: 'Speaker')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        SpeakerManager $speakerManager,
        #[MapRequestPayload(
            validationGroups: ['create']
        )] SpeakerDomainObject $dto,
    ): JsonResponse {
        $speaker = $speakerManager->createSpeakerFromDTO($dto);

        return new JsonResponse($normalizer->normalize($speaker));
    }
}
