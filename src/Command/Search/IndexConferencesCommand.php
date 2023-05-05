<?php

namespace App\Command\Search;

use App\Repository\ConferenceRepository;
use App\Service\Search\ConferenceIndexer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:search:index-conferences',
    description: 'Index Conferences',
)]
class IndexConferencesCommand extends Command
{
    public function __construct(
        public ConferenceRepository $conferenceRepository,
        public NormalizerInterface $serializer,
        protected ConferenceIndexer $conferenceIndexer
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->conferenceIndexer->reset();

        $conferences = $this->conferenceRepository->findAll();
        $this->conferenceIndexer->indexConferences($conferences);
        return Command::SUCCESS;
    }
}
