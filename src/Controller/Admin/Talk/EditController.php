<?php

namespace App\Controller\Admin\Talk;

use App\DomainObject\TalkDomainObject;
use App\Entity\Talk;
use App\Form\Type\TalkFormType;
use App\Manager\Admin\TalkManager;
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
class EditController extends AbstractController
{
    #[Route(
        path: '/admin/talks/{talk}',
        name: 'api_admin_talk_edit',
        requirements: ['talk' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
    )]
    #[OA\Tag(name: 'Talk')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        Talk $talk,
        NormalizerInterface $normalizer,
        TalkManager $talkManager,
        Request $request,
    ): JsonResponse {
        $dto = TalkDomainObject::from($talk);
        $form = $this->createForm(TalkFormType::class, $dto);
        $form->submit($request->toArray(), $clearMissing = false);

        if ($form->isSubmitted() && $form->isValid()) {
            $talk = $talkManager->updateTalkFromDTO($talk, $dto);

            return new JsonResponse($normalizer->normalize($talk));
        }

        return new JsonResponse([
            'errors' => $normalizer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
