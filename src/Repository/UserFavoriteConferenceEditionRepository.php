<?php

namespace App\Repository;

use App\Entity\UserFavoriteConferenceEdition;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteConferenceEditionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavoriteConferenceEdition::class);
    }
}
