<?php

namespace App\Repository\UserFavorite;

use App\Entity\UserFavoriteTalk;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteTalkRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteTalk::class);
    }
}
