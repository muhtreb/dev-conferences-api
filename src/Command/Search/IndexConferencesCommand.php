<?php

namespace App\Command\Search;

use App\Repository\ConferenceRepository;
use App\Service\Search\ConferenceIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:search:index-conferences',
    description: 'Index Conferences',
)]
class IndexConferencesCommand extends Command
{
    public function __construct(
        public ConferenceRepository $conferenceRepository,
        public NormalizerInterface $normalizer,
        private readonly ConferenceIndexer $conferenceIndexer
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('reset', null, null, 'Reset the index');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('reset')) {
            try {
                $this->conferenceIndexer->reset();
            } catch (\Exception) {
                // do nothing
            }
        }

        $io->title('Indexing Conferences');

        $conferences = $this->conferenceRepository->findAll();
        $this->conferenceIndexer->indexConferences($conferences);

        $io->success('Done');

        return Command::SUCCESS;
    }
}
