<?php

namespace App\Manager\Admin;

use App\DomainObject\YoutubePlaylistImportDomainObject;
use App\Entity\YoutubePlaylistImport;
use App\Enum\YoutubePlaylistImportStatusEnum;
use App\Message\ImportYoutubePlaylistMessage;
use App\Repository\YoutubePlaylistImportRepository;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class YoutubePlaylistImportManager
{
    public function __construct(
        private YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        private MessageBusInterface $bus,
    )
    {
    }

    public function createYoutubePlaylistImportFromDTO(YoutubePlaylistImportDomainObject $dto): YoutubePlaylistImport
    {
        $youtubePlaylistImport = (new YoutubePlaylistImport())
            ->setPlaylistId($dto->playlistId)
            ->setConferenceEdition($dto->conferenceEdition)
            ->setStatus(YoutubePlaylistImportStatusEnum::Pending);

        $this->youtubePlaylistImportRepository->save($youtubePlaylistImport);

        if (null !== $id = $youtubePlaylistImport->getId()) {
            $this->bus->dispatch(new ImportYoutubePlaylistMessage($id));
        }

        return $youtubePlaylistImport;
    }
}