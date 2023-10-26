<?php

namespace App\Controller\UserFavorite\Conference;

use App\Entity\User;
use App\Repository\ConferenceRepository;
use App\Repository\UserFavoriteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class ListController extends AbstractController
{
    #[Route('/user/{user}/favorite/conferences', name: 'api_user_favorite_conferences_list', methods: ['GET'])]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        User $user,
        ConferenceRepository $conferenceRepository,
        UserFavoriteRepository $userFavoriteRepository,
    ): JsonResponse {
        $favorites = [];
        foreach ($user->getUserFavoriteConferences() as $favorite) {
            $favorites[] = $serializer->normalize($favorite->getConference(), null, [
                'withEditions' => false
            ]);
        }

        return new JsonResponse($favorites);
    }
}
