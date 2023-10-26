<?php

namespace App\Repository;

use App\Entity\UserFavoriteConferenceEdition;
use App\Entity\UserFavoriteTalk;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteTalkRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteTalk::class);
    }
}
