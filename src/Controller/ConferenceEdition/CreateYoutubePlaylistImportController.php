<?php

namespace App\Controller\ConferenceEdition;

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

class CreateYoutubePlaylistImportController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}/youtube_playlist_imports',
        name: 'api_conference_edition_youtube_playlist_imports_create',
        methods: ['POST']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        YoutubePlaylistImportManager $youtubePlaylistImportManager,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        $dto = new YoutubePlaylistImportDomainObject();
        $dto->conferenceEdition = $conferenceEdition;
        $form = $this->createForm(YoutubePlaylistImportFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $youtubePlaylistImport = $youtubePlaylistImportManager->createYoutubePlaylistImportFromDTO($dto);
            return new JsonResponse($serializer->normalize($youtubePlaylistImport));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
