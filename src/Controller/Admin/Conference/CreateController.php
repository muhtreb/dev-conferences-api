<?php

namespace App\Controller\Admin\Conference;

use App\DomainObject\ConferenceDomainObject;
use App\Form\Type\ConferenceFormType;
use App\Manager\Admin\ConferenceManager;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    #[Route('/admin/conferences', name: 'api_admin_conference_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $normalizer,
        ConferenceManager $conferenceManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new ConferenceDomainObject();
        $form = $this->createForm(ConferenceFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $conference = $conferenceManager->createConferenceFromDTO($dto);
            return new JsonResponse($normalizer->normalize($conference));
        }

        return new JsonResponse([
            'errors' => $normalizer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
