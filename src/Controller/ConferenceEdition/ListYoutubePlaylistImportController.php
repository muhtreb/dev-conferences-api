<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Repository\YoutubePlaylistImportRepository;
use OpenApi\Attributes as OA;
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
    #[OA\Tag(name: 'Conference Edition')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        return new JsonResponse(
            $normalizer->normalize(
                $youtubePlaylistImportRepository->findBy(['conferenceEdition' => $conferenceEdition])
            )
        );
    }
}
