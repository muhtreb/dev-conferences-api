<?php

namespace App\Command;

use App\Api\Client\YoutubeApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:debug-youtube-playlist',
    description: 'Debug youtube playlist'
)]
class DebugYoutubePlaylistCommand extends Command
{
    public function __construct(
        public YoutubeApiClient $youtubeApiClient,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('playlistId');
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
