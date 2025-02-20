<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\ConferenceEditionDomainObject;
use App\Entity\Conference;
use App\Form\Type\ConferenceEditionFormType;
use App\Manager\Admin\ConferenceEditionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Attributes as OA;

#[IsGranted('ROLE_ADMIN')]
class CreateController extends AbstractController
{
    #[Route('/admin/conferences/{conference}/editions', name: 'api_admin_conference_edition_create', methods: ['POST'])]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        Conference $conference,
        NormalizerInterface $normalizer,
        ConferenceEditionManager $conferenceEditionManager,
        Request $request,
    ): JsonResponse {
        $dto = new ConferenceEditionDomainObject();
        $dto->conference = $conference;
        $form = $this->createForm(ConferenceEditionFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceEdition = $conferenceEditionManager->createConferenceEditionFromDTO($dto);

            return new JsonResponse($normalizer->normalize($conferenceEdition));
        }

        return new JsonResponse([
            'errors' => $normalizer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
