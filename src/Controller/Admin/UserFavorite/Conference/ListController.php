<?php

namespace App\Controller\Admin\UserFavorite\Conference;

use App\Entity\User;
use App\Repository\ConferenceRepository;
use App\Repository\UserFavorite\UserFavoriteRepository;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class ListController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/conferences',
        name: 'api_admin_user_favorite_conferences_list',
        methods: ['GET']
    )]
    #[OA\Tag(name: 'User Favorite')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        User $user,
        ConferenceRepository $conferenceRepository,
        UserFavoriteRepository $userFavoriteRepository,
    ): JsonResponse {
        $favorites = [];
        foreach ($user->getUserFavoriteConferences() as $favorite) {
            $favorites[] = $normalizer->normalize($favorite->getConference(), null, [
                'withEditions' => false,
            ]);
        }

        return new JsonResponse($favorites);
    }
}
