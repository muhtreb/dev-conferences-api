<?php

namespace App\Controller\Admin\UserFavorite;

use App\Entity\User;
use App\Repository\UserFavorite\UserFavoriteRepository;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DataController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/data',
        name: 'api_admin_user_favorite_data',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'User Favorite')]
    #[Security(name: 'Bearer')]
    public function __invoke(User $user, UserFavoriteRepository $userFavoriteRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $speakerIds = $data['speaker'] ?? [];
        $talkIds = $data['talk'] ?? [];
        $conferenceIds = $data['conference'] ?? [];
        $editionIds = $data['conferenceEdition'] ?? [];

        $matchingSpeakerIds = $userFavoriteRepository->checkUserFavoriteSpeakerIds($user, $speakerIds);
        $matchingTalkIds = $userFavoriteRepository->checkUserFavoriteTalkIds($user, $talkIds);
        $matchingConferenceIds = $userFavoriteRepository->checkUserFavoriteConferenceIds($user, $conferenceIds);
        $matchingConferenceEditionIds = $userFavoriteRepository->checkUserFavoriteConferenceEditionIds($user, $editionIds);

        return new JsonResponse([
            'speaker' => $matchingSpeakerIds,
            'talk' => $matchingTalkIds,
            'conference' => $matchingConferenceIds,
            'conferenceEdition' => $matchingConferenceEditionIds,
        ]);
    }
}
