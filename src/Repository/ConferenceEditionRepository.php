<?php

namespace App\Repository;

use App\Entity\ConferenceEdition;
use App\Entity\Talk;
use Doctrine\Persistence\ManagerRegistry;

class ConferenceEditionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConferenceEdition::class);
    }

    public function getLastEditions(int $count = 10)
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
}
