<?php

namespace App\Command\Search;

use App\Repository\SpeakerRepository;
use App\Service\Search\SpeakerIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:search:index-speakers',
    description: 'Index Speakers',
)]
class IndexSpeakersCommand extends Command
{
    public function __construct(
        public SpeakerRepository $speakerRepository,
        public SpeakerIndexer $speakerIndexer
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->speakerIndexer->reset();
        } catch (\Exception $e) {
            // do nothing
        }

        $speakers = $this->speakerRepository->findAll();
        $this->speakerIndexer->indexSpeakers($speakers);
        return Command::SUCCESS;
    }
}
