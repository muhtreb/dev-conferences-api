<?php

namespace App\Command\Search;

use App\Repository\ConferenceEditionRepository;
use App\Service\Search\ConferenceEditionIndexer;
use App\Service\SearchClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:search:index-conference-editions',
    description: 'Index Conference Editions',
)]
class IndexConferenceEditionsCommand extends Command
{
    public function __construct(
        public ConferenceEditionRepository $conferenceEditionRepository,
        public NormalizerInterface $serializer,
        private readonly ConferenceEditionIndexer $conferenceEditionIndexer
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addOption('reset', null, null, 'Reset the index');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('reset')) {
            $io->title('Resetting Index');
            try {
                $this->conferenceEditionIndexer->reset();
            } catch (\Exception) {
                // do nothing
            }
        }

        $io->title('Indexing Conference Editions');

        $conferenceEditions = $this->conferenceEditionRepository->findAll();
        $this->conferenceEditionIndexer->indexConferenceEditions($conferenceEditions);

        $io->success('Done');

        return Command::SUCCESS;
    }
}
