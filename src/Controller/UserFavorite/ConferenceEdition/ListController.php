<?php

namespace App\Controller\UserFavorite\ConferenceEdition;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[IsGranted('ROLE_ADMIN')]
class ListController extends AbstractController
{
    #[Route('/user/{user}/favorite/conference_editions', name: 'api_user_favorite_conference_editions_list', methods: ['GET'])]
    public function __invoke(
        NormalizerInterface $serializer,
        Request $request,
        User $user
    ): JsonResponse {
        $favorites = [];
        foreach ($user->getUserFavoriteConferenceEditions() as $favorite) {
            $favorites[] = $serializer->normalize($favorite->getConferenceEdition(), null, [
                'withTalks' => false
            ]);
        }

        return new JsonResponse($favorites);
    }
}
