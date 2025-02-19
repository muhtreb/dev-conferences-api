<?php

namespace App\Command\Search;

use App\Repository\SpeakerRepository;
use App\Service\Search\SpeakerIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:search:index-speakers',
    description: 'Index Speakers',
)]
class IndexSpeakersCommand extends Command
{
    public function __construct(
        public SpeakerRepository $speakerRepository,
        public SpeakerIndexer $speakerIndexer,
    ) {
        parent::__construct();
    }

    public function configure(): void
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
                $this->speakerIndexer->reset();
            } catch (\Exception) {
                // do nothing
            }
        }

        $io->title('Indexing Speakers');

        $speakers = $this->speakerRepository->findAll();
        $this->speakerIndexer->indexSpeakers($speakers);

        $io->success('Done');

        return Command::SUCCESS;
    }
}
