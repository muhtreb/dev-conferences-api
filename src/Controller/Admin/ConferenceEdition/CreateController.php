<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\ConferenceEditionDomainObject;
use App\Entity\Conference;
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
class CreateController extends AbstractController
{
    #[Route('/admin/conferences/{conference}/editions', name: 'api_admin_conference_edition_create', methods: ['POST'])]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $serializer,
        ConferenceEditionManager $conferenceEditionManager,
        Request $request,
    ): JsonResponse
    {
        $dto = new ConferenceEditionDomainObject();
        $dto->conference = $conference;
        $form = $this->createForm(ConferenceEditionFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceEdition = $conferenceEditionManager->createConferenceEditionFromDTO($dto);
            return new JsonResponse($serializer->normalize($conferenceEdition));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
