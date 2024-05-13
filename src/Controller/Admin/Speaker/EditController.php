<?php

namespace App\Controller\Admin\Speaker;

use App\DomainObject\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Form\Type\SpeakerFormType;
use App\Manager\Admin\SpeakerManager;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
        path: '/admin/speakers/{speaker}',
        name: 'api_admin_speaker_edit',
        requirements: ['speaker' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
    )]
    public function __invoke(
        Speaker $speaker,
        NormalizerInterface $serializer,
        SpeakerManager $speakerManager,
        Request $request,
    ): JsonResponse {
        $dto = SpeakerDomainObject::from($speaker);
        $form = $this->createForm(SpeakerFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $speaker = $speakerManager->updateSpeakerFromDTO($speaker, $dto);
            return new JsonResponse($serializer->normalize($speaker));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
