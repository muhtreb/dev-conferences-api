<?php

namespace App\Controller\ConferenceEdition;

use App\Entity\Conference;
use App\Repository\ConferenceEditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/conferences/{conference}/editions',
        name: 'api_conference_edition_list',
        requirements: ['conference' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}']
    )]
    public function __invoke(
        Conference $conference,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $serializer,
    ): JsonResponse
    {
        $conferenceEditions = new ArrayCollection($conferenceEditionRepository->findBy(['conference' => $conference]));
        return new JsonResponse($serializer->normalize($conferenceEditions, null, [
            'withTalks' => false,
            'withPlaylistImports' => false
        ]));
    }
}
