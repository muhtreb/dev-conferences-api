<?php

namespace App\Command\DbMigration;

use App\Repository\ConferenceRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:update-conferences-images',
    description: 'Update conferences images',
)]
class UpdateConferencesImagesCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public ConferenceRepository $conferenceRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(
            <<<SQL
            SELECT * FROM meetings
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch meetings');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            if (null !== $conference = $this->conferenceRepository->findOneBy(['legacyId' => $result['id']])) {
                $conference
                    ->setHeaderImageUrl(!empty($result['header_image']) ? $result['header_image'] : null)
                    ->setThumbnailImageUrl(!empty($result['thumbnail_image']) ? $result['thumbnail_image'] : null);

                $this->conferenceRepository->save($conference);
            }
        }

        return Command::SUCCESS;
    }
}
