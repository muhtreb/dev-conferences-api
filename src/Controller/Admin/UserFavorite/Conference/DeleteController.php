<?php

namespace App\Controller\Admin\UserFavorite\Conference;

use App\Entity\Conference;
use App\Entity\User;
use App\Repository\UserFavoriteConferenceRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class DeleteController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/conferences/{conference}/delete',
        name: 'api_admin_user_favorite_conference_delete',
        methods: ['POST']
    )]
    public function __invoke(
        NormalizerInterface $normalizer,
        Request $request,
        Conference $conference,
        User $user,
        UserFavoriteConferenceRepository $userFavoriteRepository
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'conference' => $conference,
            'user' => $user,
        ]);

        if ($userFavorite) {
            $userFavoriteRepository->remove($userFavorite);
        }

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
