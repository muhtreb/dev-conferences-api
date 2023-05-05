<?php

namespace App\Command\DbMigration;

use App\Entity\SpeakerTalk;
use App\Entity\YoutubePlaylistImport;
use App\Enum\YoutubePlaylistImportStatusEnum;
use App\Repository\ConferenceEditionRepository;
use App\Repository\SpeakerRepository;
use App\Repository\SpeakerTalkRepository;
use App\Repository\TalkRepository;
use App\Repository\YoutubePlaylistImportRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:youtube-playlist-import',
    description: 'Migrate youtube playlist import',
)]
class MigrateYoutubePlaylistImportCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public YoutubePlaylistImportRepository $youtubePlaylistImportRepository,
        public ConferenceEditionRepository $conferenceEditionRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(<<<SQL
            SELECT * FROM meetings_editions
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch meetings_editions');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            $urls = explode("\n", $result['playlists_urls']);
            foreach ($urls as $url) {
                if (!str_contains($url, 'youtube')) {
                    continue;
                }

                $parsedUrl = parse_url($result['video_url']);
                if (!is_array($parsedUrl) || !isset($parsedUrl['query'])) {
                    continue;
                }

                parse_str($parsedUrl['query'], $youtubeVideoUrlQuery);

                $youtubePlaylistImport = (new YoutubePlaylistImport())
                    ->setConferenceEdition($this->conferenceEditionRepository->findOneBy(['legacyId' => $result['id']]))
                    ->setStatus(YoutubePlaylistImportStatusEnum::Success)
                    ->setPlaylistId($youtubeVideoUrlQuery['list'])
                    ->setData([]);

                $this->youtubePlaylistImportRepository->save($youtubePlaylistImport);
            }
        }
        return Command::SUCCESS;
    }
}