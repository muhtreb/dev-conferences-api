<?php

namespace App\Command\DbMigration;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cocur\Slugify\Slugify;

#[AsCommand(
    name: 'app:db-migration:conferences',
    description: 'Migrate conferences',
)]
class MigrateConferencesCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public ConferenceRepository $conferenceRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(<<<SQL
            SELECT * FROM meetings
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch meetings');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            $conference = (new Conference())
                ->setLegacyId($result['id'])
                ->setName($result['name'])
                ->setSlug((new Slugify())->slugify($result['name']))
                ->setDescription(!empty($result['description']) ? $result['description'] : null)
                ->setWebsite(!empty($result['website']) ? $result['website'] : null)
                ->setTwitter(!empty($result['twitter']) ? $result['twitter'] : null)
                ->setHeaderImageUrl(!empty($result['header']) ? $result['header'] : null)
                ->setThumbnailImageUrl(!empty($result['thumbnail']) ? $result['thumbnail'] : null);
            $this->conferenceRepository->save($conference);
        }
        return Command::SUCCESS;
    }
}