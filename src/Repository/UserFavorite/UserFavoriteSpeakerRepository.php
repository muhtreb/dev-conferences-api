<?php

namespace App\Repository\UserFavorite;

use App\Entity\UserFavoriteSpeaker;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteSpeakerRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteSpeaker::class);
    }
}
