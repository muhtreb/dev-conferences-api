<?php

namespace App\Repository\UserFavorite;

use App\Entity\UserFavoriteConferenceEdition;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteConferenceEditionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteConferenceEdition::class);
    }
}
