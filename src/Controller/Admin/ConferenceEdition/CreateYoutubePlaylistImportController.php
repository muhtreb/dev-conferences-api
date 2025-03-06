<?php

namespace App\Controller\Admin\ConferenceEdition;

use App\DomainObject\YoutubePlaylistImportDomainObject;
use App\Entity\ConferenceEdition;
use App\Manager\Admin\YoutubePlaylistImportManager;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateYoutubePlaylistImportController extends AbstractController
{
    #[Route(
        path: '/admin/conferences/editions/{conferenceEdition}/youtube_playlist_imports',
        name: 'api_admin_conference_edition_youtube_playlist_imports_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'Conference Edition')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        ConferenceEdition $conferenceEdition,
        Request $request,
        YoutubePlaylistImportManager $youtubePlaylistImportManager,
        NormalizerInterface $normalizer,
        #[MapRequestPayload(
            validationGroups: ['create']
        )] YoutubePlaylistImportDomainObject $dto,
    ): JsonResponse {
        $youtubePlaylistImport = $youtubePlaylistImportManager->createYoutubePlaylistImportFromDTO($conferenceEdition, $dto);

        return new JsonResponse($normalizer->normalize($youtubePlaylistImport));
    }
}
