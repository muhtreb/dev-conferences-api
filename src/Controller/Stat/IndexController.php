<?php

namespace App\Controller\Stat;

use App\Repository\ConferenceEditionRepository;
use App\Repository\ConferenceRepository;
use App\Repository\SpeakerRepository;
use App\Repository\TalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexController extends AbstractController
{
    #[Route(
        path: '/stats',
        name: 'api_stats_index',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        ConferenceEditionRepository $conferenceEditionRepository,
        SpeakerRepository $speakerRepository,
        TalkRepository $talkRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse
    {
        $conferencesCount = $conferenceRepository->count([]);
        $editionsCount = $conferenceEditionRepository->count([]);
        $speakersCount = $speakerRepository->count([]);
        $talksCount = $talkRepository->count([]);

        $editionsStatsByYear = $conferenceEditionRepository->getEditionsStatsByYear();
        $editionsStats = array_column($editionsStatsByYear, 'count', 'year');
        $talksStatsByYear = $talkRepository->getTalksStatsByYear();
        $talksStats = array_column($talksStatsByYear, 'count', 'year');

        return new JsonResponse([
            'conferences' => [
                'total' => $conferencesCount,
            ],
            'editions' => [
                'total' => $editionsCount,
                'by_year' => $editionsStats,
            ],
            'speakers'  => [
                'total' => $speakersCount,
            ],
            'talks' => [
                'total' => $talksCount,
                'by_year' => $talksStats,
            ]
        ]);
    }
}
