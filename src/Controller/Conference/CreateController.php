<?php

namespace App\Controller\Conference;

use App\DomainObject\ConferenceDomainObject;
use App\Form\Type\ConferenceFormType;
use App\Manager\Admin\ConferenceManager;
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
    #[Route('/conferences', name: 'api_conference_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        ConferenceManager $conferenceManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new ConferenceDomainObject();
        $form = $this->createForm(ConferenceFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $conference = $conferenceManager->createConferenceFromDTO($dto);
            return new JsonResponse($serializer->normalize($conference));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
