<?php

namespace App\Controller\Admin\UserFavorite\Conference;

use App\Entity\Conference;
use App\Entity\User;
use App\Repository\UserFavorite\UserFavoriteConferenceRepository;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class DeleteController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/conferences/{conference}/delete',
        name: 'api_admin_user_favorite_conference_delete',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'User Favorite')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        Conference $conference,
        User $user,
        UserFavoriteConferenceRepository $userFavoriteRepository,
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
