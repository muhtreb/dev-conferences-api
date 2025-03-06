<?php

namespace App\Controller\Admin\Tag;

use App\DomainObject\TagDomainObject;
use App\Manager\Admin\TagManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    #[Route('/admin/tags', name: 'api_admin_tag_create', methods: ['POST'])]
    #[OA\Tag(name: 'Tag')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        TagManager $tagManager,
        Request $request,
        #[MapRequestPayload(
            validationGroups: ['create']
        )] TagDomainObject $dto,
    ): JsonResponse {
        return new JsonResponse(
            $normalizer->normalize($tagManager->createTagFromDTO($dto))
        );
    }
}
