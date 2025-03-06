<?php

namespace App\Controller\Admin\Talk;

use App\Controller\FormViolationsTrait;
use App\DomainObject\TalkDomainObject;
use App\Form\Type\TalkFormType;
use App\Manager\Admin\TalkManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    use FormViolationsTrait;

    #[Route('/admin/talks', name: 'api_admin_talks_create', methods: ['POST'])]
    #[OA\Tag(name: 'Talk')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        TalkManager $talkManager,
        Request $request,
    ): JsonResponse {
        $payload = $request->toArray();
        $dto = new TalkDomainObject();
        $form = $this->createForm(TalkFormType::class, $dto);
        $form->submit($payload);

        if ($form->isSubmitted() && $form->isValid()) {
            $talk = $talkManager->createTalkFromDTO($dto);

            return new JsonResponse($normalizer->normalize($talk));
        }

        return $this->getFormErrorResponse($form, $normalizer);
    }
}
