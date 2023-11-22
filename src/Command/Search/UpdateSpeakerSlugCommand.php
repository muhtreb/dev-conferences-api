<?php

namespace App\Command\Search;

use App\Entity\ConferenceEdition;
use App\Entity\Speaker;
use App\Repository\ConferenceEditionRepository;
use App\Repository\SpeakerRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-speaker-slug',
    description: 'Update speaker slug',
)]
class UpdateSpeakerSlugCommand extends Command
{
    private static array $generatedSlugs = [];
    private static int $countSlugGenerated = 1;

    public function __construct(
        public SpeakerRepository $speakerRepository,
        public EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        $flushBatchSize = 100;
        foreach ($this->iterate(10000) as $speaker) {
            $speakerSlug = $this->generateSpeakerSlug($speaker);
            self::$generatedSlugs[] = $speakerSlug;
            $speaker->setSlug($speakerSlug);

            $this->speakerRepository->save($speaker, false);

            $count++;
            if ($count % $flushBatchSize === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                self::$generatedSlugs = [];
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function generateSpeakerSlug(Speaker $speaker): string
    {
        $name = $speaker->getFirstName() . ' ' . $speaker->getLastName();
        if (self::$countSlugGenerated > 1) {
            $name .= ' ' . (self::$countSlugGenerated - 1);
        }
        $slug = (new Slugify())->slugify($name);
        if (in_array($slug, self::$generatedSlugs) || $this->speakerRepository->checkSlugExists($slug, $speaker->getId())) {
            self::$countSlugGenerated++;
            return $this->generateSpeakerSlug($speaker);
        }

        self::$countSlugGenerated = 1;
        return $slug;
    }

    private function iterate(int $batchSize = 100): \Generator
    {
        $leftBoundary = '00000000-0000-0000-0000-000000000000';
        $queryBuilder = $this->speakerRepository->createQueryBuilder('s');

        do {
            $qb = clone $queryBuilder;
            $qb->andWhere('s.id > :leftBoundary')
                ->setParameter('leftBoundary', $leftBoundary)
                ->orderBy('s.id', 'ASC')
                ->setMaxResults($batchSize);

            $lastReturnedSpeaker = null;
            foreach ($qb->getQuery()->toIterable() as $lastReturnedSpeaker) {
                yield $lastReturnedSpeaker;
            }

            if ($lastReturnedSpeaker) {
                $leftBoundary = $lastReturnedSpeaker->getId();
            }
        } while (null !== $lastReturnedSpeaker);
    }
}
