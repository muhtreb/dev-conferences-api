<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }
}
