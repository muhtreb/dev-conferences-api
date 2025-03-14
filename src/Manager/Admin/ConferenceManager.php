<?php

namespace App\Manager\Admin;

use App\DomainObject\ConferenceDomainObject;
use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use App\Service\Search\Indexer\ConferenceIndexer;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

readonly class ConferenceManager
{
    public function __construct(
        private ConferenceRepository $conferenceRepository,
        private ConferenceIndexer $conferenceIndexer,
        private SluggerInterface $slugger,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function createConferenceFromDTO(ConferenceDomainObject $dto): Conference
    {
        $conference = (new Conference())
            ->setName($dto->name)
            ->setSlug($this->slugger->slug($dto->name)->lower())
            ->setDescription($dto->description)
            ->setWebsite($dto->website)
            ->setTwitter($dto->twitter)
            ->setThumbnailImageUrl($dto->thumbnailImageUrl);

        $this->conferenceRepository->save($conference);

        $this->conferenceIndexer->indexConference($conference);

        $this->invalidateSearchCache();

        return $conference;
    }

    public function updateConferenceFromDTO(Conference $conference, ConferenceDomainObject $dto): Conference
    {
        $conference
            ->setName($dto->name)
            ->setSlug($this->slugger->slug($dto->name)->lower())
            ->setDescription($dto->description)
            ->setWebsite($dto->website)
            ->setTwitter($dto->twitter)
            ->setThumbnailImageUrl($dto->thumbnailImageUrl);

        $this->conferenceRepository->save($conference);

        $this->conferenceIndexer->indexConference($conference);

        $this->invalidateSearchCache();

        return $conference;
    }

    public function removeConference(Conference $conference): void
    {
        $id = $conference->getId();

        $this->conferenceRepository->remove($conference);

        $this->conferenceIndexer->removeConferenceById($id);

        $this->invalidateSearchCache();
    }

    private function invalidateSearchCache(): void
    {
        $this->cache->invalidateTags(['search-conferences']);
    }
}
