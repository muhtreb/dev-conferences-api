<?php

namespace App\Controller\UserFavorite\Speaker;

use App\Entity\Speaker;
use App\Entity\User;
use App\Entity\UserFavoriteSpeaker;
use App\Repository\UserFavoriteRepository;
use App\Repository\UserFavoriteSpeakerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class DeleteController extends AbstractController
{
    #[Route('/user/{user}/favorite/speakers/{speaker}/delete', name: 'api_user_favorite_speaker_delete', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        Speaker $speaker,
        User $user,
        UserFavoriteSpeakerRepository $userFavoriteRepository
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'speaker' => $speaker,
            'user' => $user,
        ]);

        if ($userFavorite) {
            $userFavoriteRepository->remove($userFavorite);
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
