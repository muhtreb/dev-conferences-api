<?php

namespace App\Command\DbMigration;

use App\Entity\Conference;
use App\Entity\ConferenceEdition;
use App\Repository\ConferenceEditionRepository;
use App\Repository\ConferenceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:conference-editions',
    description: 'Migrate conference editions',
)]
class MigrateConferenceEditionsCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public ConferenceRepository $conferenceRepository,
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
            $conferenceEdition = (new ConferenceEdition())
                ->setLegacyId($result['id'])
                ->setConference($this->conferenceRepository->findOneBy(['legacyId' => $result['meeting_id']]))
                ->setName($result['name'])
                ->setDescription(!empty($result['description']) ? $result['description'] : null)
                ->setWebsite(!empty($result['website']) ? $result['website'] : null)
                ->setStartDate(!empty($result['start_date']) ? new \DateTime($result['start_date']) : null)
                ->setEndDate(!empty($result['end_date']) ? new \DateTime($result['end_date']) : null);
            $this->conferenceEditionRepository->save($conferenceEdition);
        }

        return Command::SUCCESS;
    }
}