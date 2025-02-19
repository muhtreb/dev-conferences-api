<?php

namespace App\Command\Search;

use App\Repository\TalkRepository;
use App\Service\Search\TalkIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:search:index-talks',
    description: 'Index Talks',
)]
class IndexTalksCommand extends Command
{
    public function __construct(
        public TalkRepository $talkRepository,
        public TalkIndexer $talkIndexer
    ) {
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
            $io->title('Resetting Index');

            try {
                $this->talkIndexer->reset();
            } catch (\Exception) {
                // do nothing
            }
        }


        $io->title('Indexing Talks');

        $progressBar = $io->createProgressBar($this->talkRepository->count([]));
        $progressBar->start();

        $offset = 0;
        $limit = 200;
        do {
            $talks = $this->talkRepository->findBy([], null, $limit, $offset);
            $progressBar->advance(count($talks));
            $this->talkIndexer->indexTalks($talks);
            $offset += $limit;
        } while (count($talks) === $limit);

        $progressBar->finish();

        return Command::SUCCESS;
    }
}
