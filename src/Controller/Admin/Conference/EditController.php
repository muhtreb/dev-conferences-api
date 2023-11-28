<?php

namespace App\Controller\Admin\Conference;

use App\DomainObject\ConferenceDomainObject;
use App\Entity\Conference;
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
class EditController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/{conference}',
        name: 'api_admin_conference_edit',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['PATCH']
    )]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $serializer,
        ConferenceManager $conferenceManager,
        Request $request,
    ): JsonResponse
    {
        $dto = ConferenceDomainObject::from($conference);
        $form = $this->createForm(ConferenceFormType::class, $dto);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $conference = $conferenceManager->updateConferenceFromDTO($conference, $dto);
            return new JsonResponse($serializer->normalize($conference));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
