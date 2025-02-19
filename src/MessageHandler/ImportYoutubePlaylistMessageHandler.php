<?php

namespace App\MessageHandler;

use App\Entity\YoutubePlaylistImport;
use App\Manager\Admin\ImportYoutubePlaylistManager;
use App\Message\ImportYoutubePlaylistMessage;
use App\Repository\YoutubePlaylistImportRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ImportYoutubePlaylistMessageHandler
{
    public function __construct(
        private ImportYoutubePlaylistManager $importYoutubePlaylistManager,
        private YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
    ) {
    }

    public function __invoke(ImportYoutubePlaylistMessage $message): void
    {
        $youtubePlaylistImport = $this->youtubePlaylistImportRepository->find($message->youtubePlaylistImportId);
        if ($youtubePlaylistImport instanceof YoutubePlaylistImport) {
            $this->importYoutubePlaylistManager->processYoutubePlaylistImport($youtubePlaylistImport);
        }
    }
}
