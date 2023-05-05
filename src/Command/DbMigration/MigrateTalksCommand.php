<?php

namespace App\Command\DbMigration;

use App\Entity\Talk;
use App\Repository\ConferenceEditionRepository;
use App\Repository\TalkRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:talks',
    description: 'Migrate talks',
)]
class MigrateTalksCommand extends Command
{
    use MysqlConnectionTrait;
    use PostgresqlConnectionTrait;

    public function __construct(
        public TalkRepository $talkRepository,
        public ConferenceEditionRepository $conferenceEditionRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(<<<SQL
            SELECT * FROM talks
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch talks');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            if (!str_contains($result['video_url'], 'youtube')) {
                continue;
            }

            $parsedUrl = parse_url($result['video_url']);
            if (!is_array($parsedUrl) || !isset($parsedUrl['query'])) {
                continue;
            }

            parse_str($parsedUrl['query'], $youtubeVideoUrlQuery);

            $videoApiData = json_decode($result['video_api_data'], true);

            $talk = (new Talk())
                ->setLegacyId($result['id'])
                ->setConferenceEdition($this->conferenceEditionRepository->findOneBy(['legacyId' => $result['meeting_edition_id']]))
                ->setName($result['name'])
                ->setDescription(!empty($result['description']) ? $result['description'] : null)
                ->setDate(!empty($result['date']) ? new \DateTime($result['date']) : new \DateTime())
                ->setPosition(!empty($result['position']) ? $result['position'] : null)
                ->setYoutubeId($youtubeVideoUrlQuery['v'])
                ->setThumbnailImageUrl(!empty($result['thumbnail_image']) ? $result['thumbnail_image'] : null)
                ->setPosterImageUrl(!empty($result['background_image']) ? $result['background_image'] : null)
                ->setDuration(!empty($result['duration']) ? $result['duration'] : null)
                ->setApiData($videoApiData['data']);
            $this->talkRepository->save($talk);
        }
        return Command::SUCCESS;
    }
}