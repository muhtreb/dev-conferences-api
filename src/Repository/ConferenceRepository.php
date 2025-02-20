<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ConferenceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function getConferencesByIds(array $ids): array
    {
        return $this->getConferencesQueryBuilder()
            ->andWhere('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function getConferences($filters = [], $sort = [], int|bool $limit = 10, int $offset = 0): array
    {
        $qb = $this->getConferencesQueryBuilder($filters)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        foreach ($sort as $field => $order) {
            $qb->addOrderBy('c.'.$field, $order);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function countConferences($filters = []): int
    {
        return $this->getConferencesQueryBuilder($filters)
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getConferencesQueryBuilder($filters = []): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->select('c, t')
            ->leftJoin('c.tags', 't');
    }
}
