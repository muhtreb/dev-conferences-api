<?php

namespace App\Repository\UserFavorite;

use App\Entity\User;
use App\Entity\UserFavorite;
use App\Entity\UserFavoriteConference;
use App\Entity\UserFavoriteConferenceEdition;
use App\Entity\UserFavoriteSpeaker;
use App\Entity\UserFavoriteTalk;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserFavoriteRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavorite::class);
    }

    public function checkUserFavoriteSpeakerIds(User $user, array $speakerIds): array
    {
        $query = $this
            ->_em
            ->createQueryBuilder()
            ->from(UserFavoriteSpeaker::class, 'uf')
            ->select('s.id')
            ->join('uf.speaker', 's')
            ->where('uf.user = :user')
            ->andWhere('s.id IN (:speakerIds)')
            ->setParameter('user', $user)
            ->setParameter('speakerIds', $speakerIds)
            ->getQuery();

        $results = $query->getResult();

        return array_map(fn($result) => $result['id'], $results);
    }

    public function checkUserFavoriteTalkIds(User $user, array $talkIds): array
    {
        $query = $this
            ->_em
            ->createQueryBuilder()
            ->from(UserFavoriteTalk::class, 'uf')
            ->select('t.id')
            ->join('uf.talk', 't')
            ->where('uf.user = :user')
            ->andWhere('t.id IN (:talkIds)')
            ->setParameter('user', $user)
            ->setParameter('talkIds', $talkIds)
            ->getQuery();

        $results = $query->getResult();

        return array_map(fn($result) => $result['id'], $results);
    }

    public function checkUserFavoriteConferenceIds(User $user, array $conferenceIds): array
    {
        $qb = $this
            ->_em
            ->createQueryBuilder();

        $results = $qb
            ->from(UserFavoriteConference::class, 'uf')
            ->select('c.id')
            ->join('uf.conference', 'c')
            ->where('uf.user = :user')
            ->andWhere($qb->expr()->in('c', ':conferenceIds'))
            ->setParameter('user', $user)
            ->setParameter('conferenceIds', $conferenceIds)
            ->getQuery()
            ->getResult();

        return array_map(fn($result) => $result['id'], $results);
    }

    public function checkUserFavoriteConferenceEditionIds(User $user, array $editionIds): array
    {
        $query = $this
            ->_em
            ->createQueryBuilder()
            ->from(UserFavoriteConferenceEdition::class, 'uf')
            ->select('e.id')
            ->join('uf.conferenceEdition', 'e')
            ->where('uf.user = :user')
            ->andWhere('e.id IN (:editionIds)')
            ->setParameter('user', $user)
            ->setParameter('editionIds', $editionIds)
            ->getQuery();

        $results = $query->getResult();

        return array_map(fn($result) => $result['id'], $results);
    }
}
