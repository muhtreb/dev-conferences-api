<?php

namespace App\Controller\Admin\UserFavorite\Talk;

use App\Entity\User;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class ListController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/talks',
        name: 'api_admin_user_favorite_talks_list',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'User Favorite')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        User $user,
    ): JsonResponse {
        $favorites = [];
        foreach ($user->getUserFavoriteTalks() as $favorite) {
            $favorites[] = $normalizer->normalize($favorite->getTalk(), null, [
                'withEditions' => false,
            ]);
        }

        return new JsonResponse($favorites);
    }
}
