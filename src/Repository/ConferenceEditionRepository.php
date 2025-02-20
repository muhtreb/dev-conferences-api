<?php

namespace App\Repository;

use App\Entity\ConferenceEdition;
use App\Entity\Talk;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class ConferenceEditionRepository extends AbstractRepository implements CheckSlugExistsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConferenceEdition::class);
    }

    public function getLatestEditions(int $count = 10)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->andWhere($qb->expr()->isNotNull('e.startDate'))
            ->join(Talk::class, 't', 'WITH', 't.conferenceEdition = e.id')
            ->orderBy('e.startDate', 'DESC')
            ->groupBy('e.id')
            ->getQuery()
            ->setMaxResults($count)
            ->getResult();
    }

    public function checkSlugExists(string $slug, ?Uuid $uuid = null): bool
    {
        $connection = $this->_em->getConnection();
        $query = <<<SQL
           SELECT slug
           FROM conference_edition
           WHERE slug = :slug
        SQL;

        if (null !== $uuid) {
            $query .= ' AND id != :editionId';
        }

        $stmt = $connection->prepare($query);
        $stmt->bindValue('slug', $slug);
        if (null !== $uuid) {
            $stmt->bindValue('editionId', $uuid);
        }
        $result = $stmt->execute();

        return false !== $result->fetchOne();
    }

    public function getEditionsStatsByYear()
    {
        $connection = $this->_em->getConnection();
        $query = <<<SQL
            SELECT COUNT(*) AS count, EXTRACT(YEAR FROM start_date) AS year
            FROM conference_edition
            GROUP BY year
            ORDER BY year DESC
        SQL;

        $stmt = $connection->prepare($query);
        $result = $stmt->execute();

        return $result->fetchAllAssociative();
    }
}
