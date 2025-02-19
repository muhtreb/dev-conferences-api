<?php

namespace App\Command;

use App\Api\Client\YoutubeApiClientInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:debug-youtube-playlist',
    description: 'Debug youtube playlist'
)]
class DebugYoutubePlaylistCommand extends Command
{
    public function __construct(
        public YoutubeApiClientInterface $youtubeApiClient,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('playlistId', InputArgument::REQUIRED, 'Playlist ID');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $playlistId = $input->getArgument('playlistId');

        $playlist = $this->youtubeApiClient->getPlaylistById($playlistId);
        $playlistItems = $this->youtubeApiClient->getPlaylistItemsById($playlistId);

        dump($playlist);
        dump($playlistItems);

        return Command::SUCCESS;
    }
}
