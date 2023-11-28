<?php

namespace App\Controller\Admin\Talk;

use App\DomainObject\TalkDomainObject;
use App\Form\Type\TalkFormType;
use App\Manager\Admin\TalkManager;
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
    #[Route('/admin/talks', name: 'api_admin_talks_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        TalkManager $talkManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new TalkDomainObject();
        $form = $this->createForm(TalkFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $talk = $talkManager->createTalkFromDTO($dto);
            return new JsonResponse($serializer->normalize($talk));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
