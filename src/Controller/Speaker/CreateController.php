<?php

namespace App\Controller\Speaker;

use App\DomainObject\SpeakerDomainObject;
use App\Form\Type\SpeakerFormType;
use App\Manager\Admin\SpeakerManager;
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
    #[Route('/speakers', name: 'api_speaker_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        SpeakerManager $speakerManager,
        Request $request,
    ): JsonResponse {
        $dto = new SpeakerDomainObject();
        $form = $this->createForm(SpeakerFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $speaker = $speakerManager->createSpeakerFromDTO($dto);
            return new JsonResponse($serializer->normalize($speaker));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
