<?php

namespace App\Repository;

use App\Entity\UserFavoriteSpeaker;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteSpeakerRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteSpeaker::class);
    }
}
