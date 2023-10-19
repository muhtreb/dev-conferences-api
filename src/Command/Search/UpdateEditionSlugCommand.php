<?php

namespace App\Command\Search;

use App\Entity\ConferenceEdition;
use App\Repository\ConferenceEditionRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-edition-slug',
    description: 'Update edition slug',
)]
class UpdateEditionSlugCommand extends Command
{
    private static array $generatedSlugs = [];
    private static int $countSlugGenerated = 1;

    public function __construct(
        public ConferenceEditionRepository $conferenceEditionRepository,
        public EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        $flushBatchSize = 100;
        foreach ($this->iterate(10000) as $edition) {
            $editionSlug = $this->generateEditionSlug($edition);
            self::$generatedSlugs[] = $editionSlug;
            $edition->setSlug($editionSlug);

            $this->conferenceEditionRepository->save($edition, false);

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

    private function generateEditionSlug(ConferenceEdition $conferenceEdition): string
    {
        $name = $conferenceEdition->getName();
        if (self::$countSlugGenerated > 1) {
            $name .= ' ' . (self::$countSlugGenerated - 1);
        }
        $slug = (new Slugify())->slugify($name);
        if (in_array($slug, self::$generatedSlugs) || $this->conferenceEditionRepository->checkSlugExists($slug, $conferenceEdition->getId())) {
            self::$countSlugGenerated++;
            return $this->generateEditionSlug($conferenceEdition);
        }

        self::$countSlugGenerated = 1;
        return $slug;
    }

    private function iterate(int $batchSize = 100): \Generator
    {
        $leftBoundary = '00000000-0000-0000-0000-000000000000';
        $queryBuilder = $this->conferenceEditionRepository->createQueryBuilder('ce');

        do {
            $qb = clone $queryBuilder;
            $qb->andWhere('ce.id > :leftBoundary')
                ->setParameter('leftBoundary', $leftBoundary)
                ->orderBy('ce.id', 'ASC')
                ->setMaxResults($batchSize);

            $lastReturnedConferenceEdition = null;
            foreach ($qb->getQuery()->toIterable() as $lastReturnedConferenceEdition) {
                yield $lastReturnedConferenceEdition;
            }

            if ($lastReturnedConferenceEdition) {
                $leftBoundary = $lastReturnedConferenceEdition->getId();
            }
        } while (null !== $lastReturnedConferenceEdition);
    }
}
