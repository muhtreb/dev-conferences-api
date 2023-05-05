<?php

namespace App\Repository;

use App\Entity\Speaker;
use App\Entity\SpeakerTalk;
use App\Entity\Talk;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class TalkRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Talk::class);
    }

    public function getSpeakerTalks(Speaker $speaker): ArrayCollection
    {
        $results = $this->_em->createQueryBuilder()
            ->select('st', 't')
            ->from(SpeakerTalk::class, 'st')
            ->join('st.talk', 't')
            ->andWhere('st.speaker = :speaker')
            ->setParameter('speaker', $speaker)
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getResult();

        $talks = new ArrayCollection();
        if (is_iterable($results)) {
            foreach ($results as $result) {
                $talks->add($result->getTalk());
            }
        }
        return $talks;
    }

    public function countSpeakerTalks(Speaker $speaker): int
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb->select($qb->expr()->count('t'))
            ->from(SpeakerTalk::class, 'st')
            ->join('st.talk', 't')
            ->andWhere('st.speaker = :speaker')
            ->setParameter('speaker', $speaker)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getIterator(): iterable
    {
        return $this->createQueryBuilder('t')->getQuery()->toIterable();
    }

    public function clearEM(): void
    {
        $this->_em->clear();
    }
}
