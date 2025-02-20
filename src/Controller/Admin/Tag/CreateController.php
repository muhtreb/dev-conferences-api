<?php

namespace App\Controller\Admin\Tag;

use App\DomainObject\TagDomainObject;
use App\Form\Type\TagFormType;
use App\Manager\Admin\TagManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    ): JsonResponse {
        $dto = new TagDomainObject();
        $form = $this->createForm(TagFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            return new JsonResponse(
                $normalizer->normalize($tagManager->createTagFromDTO($dto))
            );
        }

        return new JsonResponse([
            'errors' => $normalizer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
