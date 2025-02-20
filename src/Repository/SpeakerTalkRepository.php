<?php

namespace App\Repository;

use App\Entity\SpeakerTalk;
use Doctrine\Persistence\ManagerRegistry;

class SpeakerTalkRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpeakerTalk::class);
    }
}
