<?php

namespace App\Command\Search;

use App\Repository\TalkRepository;
use App\Service\Search\TalkIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->talkIndexer->reset();
        } catch (\Exception) {
            // do nothing
        }

        $offset = 0;
        $limit = 200;
        do {
            $talks = $this->talkRepository->findBy([], null, $limit, $offset);
            $this->talkIndexer->indexTalks($talks);
            $offset += $limit;
        } while (count($talks) > 0);

        return Command::SUCCESS;
    }
}
