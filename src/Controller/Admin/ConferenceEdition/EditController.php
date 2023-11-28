<?php

namespace App\Controller\Admin\ConferenceEdition;

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
        path: '/admin/conferences/editions/{conferenceEdition}',
        name: 'api_admin_conference_edition_edit',
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
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
