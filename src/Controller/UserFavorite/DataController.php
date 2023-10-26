<?php

namespace App\Controller\UserFavorite;

use App\Entity\User;
use App\Repository\UserFavoriteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class DataController extends AbstractController
{
    #[Route('/user/{user}/favorite/data', name: 'api_user_favorite_date', methods: ['POST'])]
    public function __invoke(User $user, UserFavoriteRepository $userFavoriteRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $speakerIds = $data['speaker'] ?? [];
        $talkIds = $data['talk'] ?? [];
        $conferenceIds = $data['conference'] ?? [];
        $editionIds = $data['edition'] ?? [];

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

