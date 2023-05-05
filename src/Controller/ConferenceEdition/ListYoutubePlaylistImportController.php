<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Repository\YoutubePlaylistImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ListYoutubePlaylistImportController extends AbstractController
{
    #[Route(
        path: '/conferences/editions/{conferenceEdition}/youtube_playlist_imports',
        name: 'api_conference_edition_youtube_playlist_imports',
        methods: ['GET']
    )]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        NormalizerInterface $serializer
    ): JsonResponse
    {
        return new JsonResponse(
            $serializer->normalize(
                $youtubePlaylistImportRepository->findBy(['conferenceEdition' => $conferenceEdition])
            )
        );
    }
}
