<?php

namespace App\Repository;

use App\Entity\UserFavoriteConference;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteConferenceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteConference::class);
    }
}
