<?php

namespace App\Controller\ConferenceEdition;

use App\DomainObject\ConferenceEditionDomainObject;
use App\Entity\ConferenceEdition;
use App\Form\Type\ConferenceEditionFormType;
use App\Manager\Admin\ConferenceEditionManager;
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
        path: '/conferences/editions/{conferenceEdition}',
        name: 'api_conference_edition_edit',
        methods: ['PATCH'],
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        NormalizerInterface $serializer,
        ConferenceEditionManager $conferenceEditionManager,
        Request $request,
    ): JsonResponse {
        $dto = ConferenceEditionDomainObject::from($conferenceEdition);
        $form = $this->createForm(ConferenceEditionFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceEdition = $conferenceEditionManager->updateConferenceEditionFromDTO($conferenceEdition, $dto);
            return new JsonResponse($serializer->normalize($conferenceEdition));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
