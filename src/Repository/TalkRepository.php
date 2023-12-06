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

    public function checkSlugExists(string $slug, ?string $talkId = null): bool
    {
        $connection = $this->_em->getConnection();
        $query = <<<SQL
           SELECT slug
           FROM talk
           WHERE slug = :slug
        SQL;

        if ($talkId !== null) {
            $query .= ' AND id != :talkId';
        }

        $stmt = $connection->prepare($query);
        $stmt->bindValue('slug', $slug);
        if ($talkId !== null) {
            $stmt->bindValue('talkId', $talkId);
        }
        $result = $stmt->execute();

        return $result->fetchOne() !== false;
    }

    public function getIterator(): iterable
    {
        return $this->createQueryBuilder('t')->getQuery()->toIterable();
    }

    public function clearEM(): void
    {
        $this->_em->clear();
    }

    public function getTalksStatsByYear()
    {
        $connection = $this->_em->getConnection();
        $query = <<<SQL
            SELECT COUNT(*) AS count, EXTRACT(YEAR FROM date) AS year FROM talk GROUP BY year ORDER BY year DESC
        SQL;

        $stmt = $connection->prepare($query);
        $result = $stmt->execute();

        return $result->fetchAllAssociative();
    }
}
