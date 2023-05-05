<?php

namespace App\Repository;

use App\Entity\Speaker;
use App\Entity\SpeakerTalk;
use App\Entity\Talk;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class SpeakerRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Speaker::class);
    }
    public function getTalkSpeakers(Talk $talk): ArrayCollection
    {
        $results = $this->_em->createQueryBuilder()
            ->select('st', 's')
            ->from(SpeakerTalk::class, 'st')
            ->join('st.speaker', 's')
            ->andWhere('st.talk = :talk')
            ->setParameter('talk', $talk)
            ->getQuery()
            ->getResult();

        $speakers = new ArrayCollection();
        if (is_iterable($results)) {
            foreach ($results as $result) {
                $speakers->add($result->getSpeaker());
            }
        }
        return $speakers;
    }
}
