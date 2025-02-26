<?php

namespace App\Controller\Admin\UserFavorite\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Entity\User;
use App\Entity\UserFavoriteConferenceEdition;
use App\Repository\UserFavorite\UserFavoriteConferenceEditionRepository;
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
class CreateController extends AbstractController
{
    #[Route(
        path: '/admin/user/{user}/favorite/conference_editions/{conferenceEdition}/create',
        name: 'api_admin_user_favorite_conference_edition_create',
        methods: ['POST']
    )]
    #[OA\Tag(name: 'User Favorite')]
    #[Security(name: 'Bearer')]
    public function __invoke(
        NormalizerInterface $normalizer,
        ConferenceEdition $conferenceEdition,
        User $user,
        UserFavoriteConferenceEditionRepository $userFavoriteRepository,
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'conferenceEdition' => $conferenceEdition,
            'user' => $user,
        ]);

        if ($userFavorite) {
            return new JsonResponse(null, Response::HTTP_CREATED);
        }

        $userFavorite = new UserFavoriteConferenceEdition();
        $userFavorite->setConferenceEdition($conferenceEdition);
        $userFavorite->setUser($user);
        $userFavoriteRepository->save($userFavorite);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
