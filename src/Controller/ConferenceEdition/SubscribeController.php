<?php

namespace App\Controller\ConferenceEdition;

use App\DomainObject\ConferenceEditionNotificationDomainObject;
use App\Entity\ConferenceEdition;
use App\Form\Type\ConferenceEditionNotificationFormType;
use App\Manager\Admin\ConferenceEditionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubscribeController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}/subscribe',
        name: 'api_conference_edition_subscribe',
        methods: ['POST'],
        requirements: ['conferenceEdition' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]    public function __invoke(
        ConferenceEdition $conferenceEdition,
        NormalizerInterface $serializer,
        ConferenceEditionManager $conferenceEditionManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new ConferenceEditionNotificationDomainObject();
        $dto->conferenceEdition = $conferenceEdition;
        $form = $this->createForm(ConferenceEditionNotificationFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceEditionManager->createConferenceEditionNotificationFromDTO($dto);
            return new JsonResponse([]);
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
