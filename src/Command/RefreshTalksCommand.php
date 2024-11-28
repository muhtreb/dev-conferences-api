<?php

namespace App\Command;

use App\Api\Client\YoutubeApiClient;
use App\Manager\Admin\ConferenceEditionManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:refresh-talks',
    description: 'Refresh talks'
)]
class RefreshTalksCommand extends Command
{
    public function __construct(
        public ConferenceEditionManager $conferenceEditionManager,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('conferenceEditionId');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conferenceEditionId = $input->getArgument('conferenceEditionId');

        $this->conferenceEditionManager->refreshTalks($conferenceEditionId);

        return Command::SUCCESS;
    }
}
