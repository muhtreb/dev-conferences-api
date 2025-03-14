<?php

namespace App\Manager\Admin;

use App\DomainObject\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use App\Service\Search\Indexer\SpeakerIndexer;
use App\Service\SlugGenerator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

readonly class SpeakerManager
{
    public function __construct(
        private SpeakerRepository $speakerRepository,
        private SpeakerIndexer $speakerIndexer,
        #[Autowire(service: 'slug_generator.speaker')]
        private SlugGenerator $speakerSlugGenerator,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function createSpeakerFromDTO(SpeakerDomainObject $dto): Speaker
    {
        $speaker = new Speaker();

        $this->populateSpeakerFromDTO($speaker, $dto);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        $this->invalidateSearchCache();

        return $speaker;
    }

    public function updateSpeakerFromDTO(Speaker $speaker, SpeakerDomainObject $dto): Speaker
    {
        $this->populateSpeakerFromDTO($speaker, $dto);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        $this->invalidateSearchCache();

        return $speaker;
    }

    private function populateSpeakerFromDTO(Speaker $entity, SpeakerDomainObject $dto): void
    {
        $entity
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setSlug(($this->speakerSlugGenerator)($dto->firstName.' '.$dto->lastName, $dto->id))
            ->setXUsername($dto->xUsername)
            ->setSpeakerDeckUsername($dto->speakerDeckUsername)
            ->setMastodonUsername($dto->mastodonUsername)
            ->setBlueskyUsername($dto->blueskyUsername)
            ->setGithubUsername($dto->githubUsername)
            ->setWebsite($dto->website)
            ->setDescription($dto->description);
    }

    public function removeSpeaker(Speaker $speaker): void
    {
        $id = $speaker->getId();

        $this->speakerRepository->remove($speaker);

        $this->speakerIndexer->removeSpeakerById($id);

        $this->invalidateSearchCache();
    }

    private function invalidateSearchCache(): void
    {
        $this->cache->invalidateTags(['search-speakers']);
    }
}
