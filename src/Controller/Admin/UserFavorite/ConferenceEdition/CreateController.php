<?php

namespace App\Controller\Admin\UserFavorite\ConferenceEdition;

use App\Entity\ConferenceEdition;
use App\Entity\User;
use App\Entity\UserFavoriteConferenceEdition;
use App\Repository\UserFavoriteConferenceEditionRepository;
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
        path: '/admin/user/{user}/favorite/conference_editions/{conferenceEdition}/create',
        name: 'api_admin_user_favorite_conference_edition_create',
        methods: ['POST']
    )]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
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
