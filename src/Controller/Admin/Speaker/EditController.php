<?php

namespace App\Controller\Admin\Speaker;

use App\DomainObject\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Manager\Admin\SpeakerManager;
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
        path: '/admin/speakers/{speaker}',
        name: 'api_admin_speaker_edit',
        requirements: ['speaker' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
    )]
    #[OA\Tag(name: 'Speaker')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        Speaker $speaker,
        NormalizerInterface $normalizer,
        SpeakerManager $speakerManager,
        #[MapRequestPayload(
            validationGroups: ['edit']
        )] SpeakerDomainObject $dto,
    ): JsonResponse {
        $speaker = $speakerManager->updateSpeakerFromDTO($speaker, $dto);

        return new JsonResponse($normalizer->normalize($speaker));
    }
}
