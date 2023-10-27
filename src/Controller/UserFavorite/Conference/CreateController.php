<?php

namespace App\Controller\UserFavorite\Conference;

use App\Entity\Conference;
use App\Entity\User;
use App\Entity\UserFavoriteConference;
use App\Repository\UserFavoriteConferenceRepository;
use App\Repository\UserFavoriteRepository;
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
    #[Route(path: '/user/{user}/favorite/conferences/{conference}/create', name: 'api_user_favorite_conference_create', methods: ['POST'])]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        Conference $conference,
        User $user,
        UserFavoriteConferenceRepository $userFavoriteRepository,
    ): JsonResponse {
        $userFavorite = $userFavoriteRepository->findOneBy([
            'conference' => $conference,
            'user' => $user,
        ]);

        if ($userFavorite) {
            return new JsonResponse(null, Response::HTTP_CREATED);
        }

        $userFavorite = new UserFavoriteConference();
        $userFavorite->setConference($conference);
        $userFavorite->setUser($user);
        $userFavoriteRepository->save($userFavorite);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
