<?php

namespace App\Controller\Talk;

use App\DomainObject\TalkDomainObject;
use App\Entity\Talk;
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
class EditController extends AbstractController
{
    #[Route(
        path: '/talks/{talk}',
        name: 'api_talk_edit',
        methods: ['PATCH'],
        requirements: ['talk' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        Talk $talk,
        NormalizerInterface $serializer,
        TalkManager $talkManager,
        Request $request,
    ): JsonResponse
    {
        $dto = TalkDomainObject::from($talk);
        $form = $this->createForm(TalkFormType::class, $dto);
        $form->submit($request->toArray(), $clearMissing = false);

        if ($form->isSubmitted() && $form->isValid()) {
            $talk = $talkManager->updateTalkFromDTO($talk, $dto);
            return new JsonResponse($serializer->normalize($talk));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
