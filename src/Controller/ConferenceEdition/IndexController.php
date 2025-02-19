<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\Conference;
use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/conferences/{conference}/editions',
        name: 'api_conference_edition_list',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'],
        methods: ['GET']
    )]
    public function __invoke(
        Conference $conference,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $normalizer,
        Request $request,
    ): JsonResponse {
        $limit = $request->query->getInt('limit', 10);
        $offset = $request->query->getInt('offset');

        $conferenceEditions = new ArrayCollection($conferenceEditionRepository->findBy(['conference' => $conference], ['name' => 'ASC'], $limit, $offset));
        return new JsonResponse($normalizer->normalize($conferenceEditions, null, [
            'withTalks' => false,
            'withPlaylistImports' => false
        ]));
    }
}
