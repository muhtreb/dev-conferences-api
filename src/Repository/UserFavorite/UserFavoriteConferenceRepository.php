<?php

namespace App\Repository\UserFavorite;

use App\Entity\UserFavoriteConference;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteConferenceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteConference::class);
    }
}
