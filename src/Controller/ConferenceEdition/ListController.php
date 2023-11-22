<?php

namespace App\Controller\ConferenceEdition;

use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ListController extends AbstractController
{
    #[Route('/conferences/editions', name: 'api_conference_edition_list', methods: ['GET'])]
    public function __invoke(
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $serializer,
        Request $request,
    ): JsonResponse
    {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset');

        $conferenceEditions = new ArrayCollection($conferenceEditionRepository->findBy([], ['name' => 'ASC'], $limit, $offset));
        return new JsonResponse($serializer->normalize($conferenceEditions, null, [
            'withConference' => $request->query->getBoolean('withConference'),
            'withCountTalks' => $request->query->getBoolean('withCountTalks'),
            'withTalks' => $request->query->getBoolean('withTalks'),
            'withPlaylistImports' => $request->query->getBoolean('withPlaylistImports'),
        ]));
    }
}
