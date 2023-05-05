<?php

namespace App\Command\DbMigration;

use App\Api\Client\YoutubeApiClient;
use App\Helper\YoutubeApiHelper;
use App\Repository\TalkRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:update-talks-duration',
    description: 'Update talks duration',
)]
class UpdateTalksDurationCommand extends Command
{
    public function __construct(
        public TalkRepository $talkRepository,
        public YoutubeApiClient $youtubeApiClient,
        public YoutubeApiHelper $youtubeApiHelper
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $criteria = Criteria::create()->where(Criteria::expr()->isNull('duration'));
        $criteria->setMaxResults(50);
        $talks = $this->talkRepository->matching($criteria);

        foreach ($talks as $talk) {
            $output->writeln('Talk #' . $talk->getId());
            $youtubeApiData = $this->youtubeApiClient->getVideoById($talk->getYoutubeId());
            if (isset($youtubeApiData['items']) && count($youtubeApiData['items'])) {
                $talk->setApiData($youtubeApiData['items'][0]);
                $talk->setDuration($this->youtubeApiHelper->getVideoDurationInSeconds($youtubeApiData['items'][0]));
                $this->talkRepository->save($talk);
            }
        }

        return Command::SUCCESS;
    }
}
