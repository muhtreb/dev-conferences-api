<?php

namespace App\Command\DbMigration;

use App\Entity\SpeakerTalk;
use App\Repository\SpeakerRepository;
use App\Repository\SpeakerTalkRepository;
use App\Repository\TalkRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:db-migration:speaker-talk',
    description: 'Migrate speaker talk',
)]
class MigrateSpeakerTalkCommand extends Command
{
    use MysqlConnectionTrait;

    public function __construct(
        public SpeakerTalkRepository $speakerTalkRepository,
        public SpeakerRepository $speakerRepository,
        public TalkRepository $talkRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mysqlConnection = $this->getMysqlConnection();
        $results = $mysqlConnection->query(<<<SQL
            SELECT * FROM speakers_talks
        SQL
        );

        if (false === $results) {
            $output->writeln('Unable to fetch speakers_talks');
            return Command::FAILURE;
        }

        foreach ($results as $result) {
            $talk = $this->talkRepository->findOneBy(['legacyId' => $result['talk_id']]);
            if ($talk) {
                $speakerTalk = (new SpeakerTalk())
                    ->setSpeaker($this->speakerRepository->findOneBy(['legacyId' => $result['speaker_id']]))
                    ->setTalk($this->talkRepository->findOneBy(['legacyId' => $result['talk_id']]))
                    ->setMain((bool)$result['main_speaker']);
                $this->speakerTalkRepository->save($speakerTalk);
            }
        }
        return Command::SUCCESS;
    }
}