<?php

namespace App\Controller\Admin\UserFavorite\Talk;

use App\Entity\Talk;
use App\Entity\User;
use App\Entity\UserFavoriteTalk;
use App\Repository\UserFavoriteTalkRepository;
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
        path: '/admin/user/{user}/favorite/talks/{talk}/create',
        name: 'api_admin_user_favorite_talk_create',
        methods: ['POST']
    )]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        Talk $talk,
        User $user,
        UserFavoriteTalkRepository $userFavoriteRepository,
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'talk' => $talk,
            'user' => $user,
        ]);

        if ($userFavorite) {
            return new JsonResponse(null, Response::HTTP_CREATED);
        }

        $userFavorite = new UserFavoriteTalk();
        $userFavorite->setTalk($talk);
        $userFavorite->setUser($user);
        $userFavoriteRepository->save($userFavorite);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
