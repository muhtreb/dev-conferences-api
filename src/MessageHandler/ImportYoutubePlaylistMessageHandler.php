<?php

namespace App\MessageHandler;

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
        if (null !== $youtubePlaylistImport = $this->youtubePlaylistImportRepository->find($message->youtubePlaylistImportId)) {
            $this->importYoutubePlaylistManager->processYoutubePlaylistImport($youtubePlaylistImport);
        }
    }
}
