<?php

namespace App\Manager\Admin;

use App\Api\Client\YoutubeApiClient;
use App\Entity\Talk;
use App\Entity\YoutubePlaylistImport;
use App\Enum\YoutubePlaylistImportStatusEnum;
use App\Helper\YoutubeApiHelper;
use App\Repository\TalkRepository;
use App\Repository\YoutubePlaylistImportRepository;

readonly class ImportYoutubePlaylistManager
{
    public function __construct(
        private YoutubeApiClient $youtubeApiClient,
        private YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        private TalkRepository $talkRepository,
        private YoutubeApiHelper $youtubeApiHelper
    )
    {
    }

    public function processYoutubePlaylistImport(YoutubePlaylistImport $youtubePlaylistImport): void
    {
        $playlistItems = $this->youtubeApiClient->getPlaylistItemsById($youtubePlaylistImport->getPlaylistId());
        $position = 0;

        $talksToIndex = [];

        foreach ($playlistItems as $playlistItem) {
            if (!$this->youtubeApiHelper->isPublishedVideo($playlistItem)) {
                continue;
            }

            if (null !== $this->talkRepository->findOneBy([
                    'youtubeId' => $playlistItem['contentDetails']['videoId'],
                    'conferenceEdition' => $youtubePlaylistImport->getConferenceEdition()
                ])) {
                continue;
            }

            $talk = (new Talk())
                ->setConferenceEdition($youtubePlaylistImport->getConferenceEdition())
                ->setName($playlistItem['snippet']['title'])
                ->setDate($youtubePlaylistImport->getConferenceEdition()->getStartDate())
                ->setDescription($playlistItem['snippet']['description'])
                ->setYoutubeId($playlistItem['contentDetails']['videoId'])
                ->setDuration($this->youtubeApiHelper->getVideoDurationInSeconds($playlistItem))
                ->setApiData($playlistItem)
                ->setPosition($position)
                ->setThumbnailImageUrl($this->youtubeApiHelper->getThumbnailImage($playlistItem))
                ->setPosterImageUrl($this->youtubeApiHelper->getMaxResImage($playlistItem));
            $this->talkRepository->save($talk);

            $talksToIndex[] = $talk;

            $position++;
        }

        //$this->talkIndexer->indexTalks($talksToIndex);

        $youtubePlaylistImport->setStatus(YoutubePlaylistImportStatusEnum::Success);
        $youtubePlaylistImport->setData($playlistItems);
        $this->youtubePlaylistImportRepository->save($youtubePlaylistImport);
    }
}
