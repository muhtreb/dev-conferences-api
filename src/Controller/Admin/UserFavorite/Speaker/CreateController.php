<?php

namespace App\Controller\Admin\UserFavorite\Speaker;

use App\Entity\Speaker;
use App\Entity\User;
use App\Entity\UserFavoriteSpeaker;
use App\Repository\UserFavoriteSpeakerRepository;
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
    #[Route(
        path: '/admin/user/{user}/favorite/speakers/{speaker}/create',
        name: 'api_admin_user_favorite_speaker_create',
        methods: ['POST']
    )]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        Speaker $speaker,
        User $user,
        UserFavoriteSpeakerRepository $userFavoriteRepository,
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'speaker' => $speaker,
            'user' => $user,
        ]);

        if ($userFavorite) {
            return new JsonResponse(null, Response::HTTP_CREATED);
        }

        $userFavorite = new UserFavoriteSpeaker();
        $userFavorite->setSpeaker($speaker);
        $userFavorite->setUser($user);
        $userFavoriteRepository->save($userFavorite);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
