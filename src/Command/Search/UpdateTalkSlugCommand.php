<?php

namespace App\Command\Search;

use App\Entity\Talk;
use App\Repository\TalkRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-talk-slug',
    description: 'Update talk slug',
)]
class UpdateTalkSlugCommand extends Command
{
    private static array $generatedSlugs = [];
    private static int $countSlugGenerated = 1;

    public function __construct(
        public TalkRepository $talkRepository,
        public EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = 0;
        $flushBatchSize = 100;
        foreach ($this->iterate(10000) as $talk) {
            $talkSlug = $this->generateTalkSlug($talk);
            self::$generatedSlugs[] = $talkSlug;
            $talk->setSlug($talkSlug);

            $this->talkRepository->save($talk, false);

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

    private function generateTalkSlug(Talk $talk): string
    {
        $name = $talk->getName();

        if (self::$countSlugGenerated > 1) {
            $name .= ' ' . (self::$countSlugGenerated - 1);
        }

        $slug = (new Slugify())->slugify($name);
        if (in_array($slug, self::$generatedSlugs) || $this->talkRepository->checkSlugExists($slug, $talk->getId())) {
            self::$countSlugGenerated++;
            return $this->generateTalkSlug($talk);
        }

        self::$countSlugGenerated = 1;
        return $slug;
    }

    private function iterate(int $batchSize = 100): \Generator
    {
        $leftBoundary = '00000000-0000-0000-0000-000000000000';
        $queryBuilder = $this->talkRepository->createQueryBuilder('t');

        do {
            $qb = clone $queryBuilder;
            $qb
                ->andWhere('t.id > :leftBoundary')
                ->setParameter('leftBoundary', $leftBoundary)
                ->orderBy('t.id', 'ASC')
                ->setMaxResults($batchSize);

            $lastReturnedTalk = null;
            foreach ($qb->getQuery()->toIterable() as $lastReturnedTalk) {
                yield $lastReturnedTalk;
            }

            if ($lastReturnedTalk) {
                $leftBoundary = $lastReturnedTalk->getId();
            }
        } while (null !== $lastReturnedTalk);
    }
}
