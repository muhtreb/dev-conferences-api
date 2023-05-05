<?php

namespace App\Repository;

use App\Entity\ConferenceEditionNotification;
use Doctrine\Persistence\ManagerRegistry;

class ConferenceEditionNotificationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConferenceEditionNotification::class);
    }
}
