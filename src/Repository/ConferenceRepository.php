<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Persistence\ManagerRegistry;

class ConferenceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }
}
