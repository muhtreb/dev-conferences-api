<?php

namespace App\Command\Search;

use App\Repository\ConferenceEditionRepository;
use App\Service\Search\ConferenceEditionIndexer;
use App\Service\SearchClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->conferenceEditionIndexer->reset();
        $conferenceEditions = $this->conferenceEditionRepository->findAll();
        $this->conferenceEditionIndexer->indexConferenceEditions($conferenceEditions);
        return Command::SUCCESS;
    }
}
