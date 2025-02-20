<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\YoutubePlaylistImportDomainObject;
use App\Entity\ConferenceEdition;
use App\Form\Type\YoutubePlaylistImportFormType;
use App\Manager\Admin\YoutubePlaylistImportManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Attributes as OA;

class CreateYoutubePlaylistImportController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/editions/{conferenceEdition}/youtube_playlist_imports',
        name: 'api_admin_conference_edition_youtube_playlist_imports_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        YoutubePlaylistImportManager $youtubePlaylistImportManager,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $dto = new YoutubePlaylistImportDomainObject();
        $dto->conferenceEdition = $conferenceEdition;
        $form = $this->createForm(YoutubePlaylistImportFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $youtubePlaylistImport = $youtubePlaylistImportManager->createYoutubePlaylistImportFromDTO($dto);

            return new JsonResponse($normalizer->normalize($youtubePlaylistImport));
        }

        return new JsonResponse([
            'errors' => $normalizer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
