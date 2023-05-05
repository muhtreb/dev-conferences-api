<?php

namespace App\Controller\Tag;

use App\DomainObject\TagDomainObject;
use App\Form\Type\TagFormType;
use App\Manager\Admin\TagManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    #[Route('/tags', name: 'api_tag_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        TagManager $tagManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new TagDomainObject();
        $form = $this->createForm(TagFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            return new JsonResponse(
                $serializer->normalize($tagManager->createTagFromDTO($dto))
            );
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
