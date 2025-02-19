<?php

namespace App\Command;

use App\Manager\Admin\ConferenceEditionManager;
use App\Repository\ConferenceEditionRepository;
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
        public ConferenceEditionRepository $conferenceEditionRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('conferenceEditionId');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conferenceEditionId = $input->getArgument('conferenceEditionId');

        $conferenceEdition = $this->conferenceEditionRepository->find($conferenceEditionId);

        if (!$conferenceEdition) {
            $output->writeln('Conference edition not found');
            return Command::FAILURE;
        }

        $this->conferenceEditionManager->refreshTalks($conferenceEdition);

        return Command::SUCCESS;
    }
}
