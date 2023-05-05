<?php

namespace App\Command\DbMigration;

use App\Entity\Conference;
use App\Entity\ConferenceEdition;
use App\Entity\Speaker;
use App\Repository\ConferenceEditionRepository;
use App\Repository\ConferenceRepository;
use App\Repository\SpeakerRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:speakers',
    description: 'Migrate speakers',
)]
class MigrateSpeakersCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public SpeakerRepository $speakerRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(<<<SQL
            SELECT * FROM speakers
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch speakers');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            $speaker = (new Speaker())
                ->setLegacyId($result['id'])
                ->setFirstName($result['first_name'])
                ->setLastName($result['last_name'])
                ->setDescription(!empty($result['description']) ? $result['description'] : null)
                ->setWebsite(!empty($result['website']) ? $result['website'] : null)
                ->setTwitter(!empty($result['twitter']) ? $result['twitter'] : null)
                ->setGithub(!empty($result['github']) ? $result['github'] : null)
                ->setSpeakerDeck(!empty($result['speaker_deck']) ? $result['speaker_deck'] : null)
                ->setNationalityIso3(!empty($result['nationality_iso3']) ? $result['nationality_iso3'] : null)
                ->setAvatarUrl(!empty($result['picture']) ? $result['picture'] : null);
            $this->speakerRepository->save($speaker);
        }
        return Command::SUCCESS;
    }
}