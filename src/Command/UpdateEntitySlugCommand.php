<?php

namespace App\Command;

use App\Entity\SluggableEntity;
use App\Repository\CheckSlugExistsRepositoryInterface;
use App\Repository\ConferenceEditionRepository;
use App\Repository\SpeakerRepository;
use App\Repository\TalkRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-entity-slug',
    description: 'Update entity slug',
)]
class UpdateEntitySlugCommand extends Command
{
    private static array $generatedSlugs = [];
    private static int $countSlugGenerated = 1;
    private Slugify $slugify;
    private ConferenceEditionRepository|TalkRepository|SpeakerRepository $repository;

    public function __construct(
        private readonly ConferenceEditionRepository $conferenceEditionRepository,
        private readonly TalkRepository $talkRepository,
        private readonly SpeakerRepository $speakerRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->slugify = new Slugify();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity to update slug');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $entity = $input->getArgument('entity');
        $this->repository = match ($entity) {
            'talk' => $this->talkRepository,
            'speaker' => $this->speakerRepository,
            'edition' => $this->conferenceEditionRepository,
            default => null,
        };

        $io->title('Updating ' . ucfirst($entity) . ' Slug');

        $count = 0;
        $flushBatchSize = 100;

        $io->progressStart($this->repository->count([]));
        foreach ($this->iterate(10000) as $entity) {
            $slug = $this->generateSlug($entity);
            self::$generatedSlugs[] = $slug;
            $entity->setSlug($slug);

            $this->entityManager->persist($entity);

            $count++;
            if ($count % $flushBatchSize === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                self::$generatedSlugs = [];
            }

            $io->progressAdvance();
        }

        $this->entityManager->flush();

        $io->progressFinish();

        return Command::SUCCESS;
    }

    private function generateSlug(SluggableEntity $entity): string
    {
        $name = $entity->getSluggableName();
        if (self::$countSlugGenerated > 1) {
            $name .= ' ' . (self::$countSlugGenerated - 1);
        }
        $slug = $this->slugify->slugify($name);
        if (in_array($slug, self::$generatedSlugs) || $this->repository->checkSlugExists($slug, $entity->getId())) {
            self::$countSlugGenerated++;
            return $this->generateSlug($entity);
        }

        self::$countSlugGenerated = 1;
        return $slug;
    }

    private function iterate(int $batchSize = 100): \Generator
    {
        $leftBoundary = '00000000-0000-0000-0000-000000000000';
        $queryBuilder = $this->repository->createQueryBuilder('e');

        do {
            $qb = clone $queryBuilder;
            $qb->andWhere('e.id > :leftBoundary')
                ->setParameter('leftBoundary', $leftBoundary)
                ->orderBy('e.id', 'ASC')
                ->setMaxResults($batchSize);

            $latestEntity = null;
            foreach ($qb->getQuery()->toIterable() as $latestEntity) {
                yield $latestEntity;
            }

            if ($latestEntity) {
                $leftBoundary = $latestEntity->getId();
            }
        } while (null !== $latestEntity);
    }
}
